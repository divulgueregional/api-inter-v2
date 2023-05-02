<?php

class ConversorDadosCarne {

    private $codBanco = null;
    private $dadosBoleto = null;

    public function getObjetoCarneBancario(stdClass $dadosBoleto, $codigoBanco) {
        $this->codBanco = $codigoBanco;
        $this->dadosBoleto = $dadosBoleto;
        $carne = new stdClass();

        switch ($codigoBanco) {
            case '077': //Banco Inter

                $descBoletoMulta = '';
                $descBoletoMora = '';

                if ($dadosBoleto->multa->codigo == 'PERCENTUAL') {
                    $descBoletoMulta = ' MULTA DE ' . $dadosBoleto->multa->taxa . '% EM ' . date('d/m/Y', strtotime($dadosBoleto->multa->data)) . '.';
                }
                if ($dadosBoleto->multa->codigo == 'VALORFIXO') {
                    $descBoletoMulta = ' MULTA DE R$ ' . number_format($dadosBoleto->multa->valor, 2, ',', '.') . ' EM ' . date('d/m/Y', strtotime($dadosBoleto->multa->data)) . '.';
                }
                if ($dadosBoleto->mora->codigo == 'TAXAMENSAL') {
                    $descBoletoMora = ' MORA DE ' . $dadosBoleto->mora->taxa . '% A PARTIR DE ' . date('d/m/Y', strtotime($dadosBoleto->mora->data)) . '.';
                }
                if ($dadosBoleto->mora->codigo == 'VALORDIA') {
                    $descBoletoMora = ' MORA DE R$ ' . number_format($dadosBoleto->mora->valor, 2, ',', '.') . ' A PARTIR DE ' . date('d/m/Y', strtotime($dadosBoleto->mora->data)) . '.';
                }

                $carne->codigoLinhaDigitavel = $dadosBoleto->linhaDigitavel;
                $carne->codigoBarras = $dadosBoleto->codigoBarras;
                $carne->mensagemBloqueto = ($dadosBoleto->mensagem->linha1 ?? '') . ($dadosBoleto->mensagem->linha2 ?? '');

                $carne->mensagemBloqueto .= '<br>' . $descBoletoMulta;
                $carne->mensagemBloqueto .= '<br>' . $descBoletoMora;

                $carne->nossoNumero = $dadosBoleto->nossoNumero;
                $carne->nomeBanco = 'Banco Inter';
                $carne->codigoBanco = '077-9';
                $carne->dataVencimento = $this->converterData($dadosBoleto->dataVencimento, $formatoAtual = 'Y-m-d', $novoFormato = 'd/m/Y');
                $carne->valorDocumento = $dadosBoleto->valorNominal;
                $carne->numeroDocumento = $dadosBoleto->seuNumero;
                $carne->nomePagador = $dadosBoleto->pagador->nome;
                $carne->CNPJCPFPagador = $this->formatarCPFCNPJ($dadosBoleto->pagador->cpfCnpj);
                $carne->CEPPagador = $dadosBoleto->pagador->cep;
                $carne->enderecoPagador = $dadosBoleto->pagador->endereco;
                $carne->bairroPagador = $dadosBoleto->pagador->bairro;
                $carne->cidadePagador = $dadosBoleto->pagador->cidade;
                $carne->UFPagador = $dadosBoleto->pagador->uf;
                $carne->nomeEmpresa = $dadosBoleto->nomeBeneficiario;
                $carne->CNPJEmpresa = $this->formatarCPFCNPJ($dadosBoleto->cnpjCpfBeneficiario);
                $carne->agencia = '0001-9';
                $carne->codigoCedente = $dadosBoleto->contaCorrente;
                $carne->codigoAceite = 'NAO';
                $carne->dataEmissao = $this->converterData($dadosBoleto->dataEmissao, $formatoAtual = 'Y-m-d', $novoFormato = 'd/m/Y');
                $carne->carteiraCobranca = '112';
                $carne->boletoPago = $dadosBoleto->situacao == 'PAGO';
                break;

            case '001':


                $descBoletoMulta = '';
                $descBoletoMora = '';
                $descBoletoProtesto = '';

                if (in_array($dadosBoleto->codigoTipoMulta, [1, 2])) {
                    $dataMulta = str_replace('.', '/', $dadosBoleto->dataMultaTitulo);

                    if ($dadosBoleto->codigoTipoMulta == 1 && $dadosBoleto->valorMultaTituloCobranca > 0) {
                        $multa = number_format($dadosBoleto->valorMultaTituloCobranca, 2, ',', '.');
                        $descBoletoMulta = "Em $dataMulta, multa de R$ $multa.";
                    }

                    if ($dadosBoleto->codigoTipoMulta == 2 && $dadosBoleto->percentualMultaTitulo > 0) {
                        $multa = number_format(($dadosBoleto->valorAtualTituloCobranca * $dadosBoleto->percentualMultaTitulo) / 100, 2, ',', '.');
                        $descBoletoMulta = "Em $dataMulta, multa de $dadosBoleto->percentualMultaTitulo%, R$ $multa.";
                    }
                }


                if (in_array($dadosBoleto->codigoTipoJuroMora, [1, 2])) {

                    $dataVencimento = str_replace('.', '/', $dadosBoleto->dataVencimentoTituloCobranca);
                    $msgMora = '';
                    if ($dadosBoleto->codigoTipoJuroMora == 1 && $dadosBoleto->valorJuroMoraTitulo > 0) {
                        $mora = number_format($dadosBoleto->valorJuroMoraTitulo, 2, ',', '.');
                        $descBoletoMora = "Depois de $dataVencimento, mora diária de R$ $mora.";
                    }

                    if ($dadosBoleto->codigoTipoJuroMora == 2 && $dadosBoleto->percentualJuroMoraTitulo > 0) {
                        $mora = number_format(($dadosBoleto->valorAtualTituloCobranca * $dadosBoleto->percentualJuroMoraTitulo) / 100, 2, ',', '.');
                        $descBoletoMora = "Depois de $dataVencimento, mora mensal de R$ $mora.";
                    }
                }

                if (is_numeric($dadosBoleto->quantidadeDiaProtesto) && $dadosBoleto->quantidadeDiaProtesto > 0) {
                    $descBoletoProtesto = "Sujeito a protesto após $dadosBoleto->quantidadeDiaProtesto dias do vencimento";
                }



                $carne->codigoLinhaDigitavel = $dadosBoleto->codigoLinhaDigitavel;
                $carne->codigoBarras = $dadosBoleto->textoCodigoBarrasTituloCobranca;
                $carne->mensagemBloqueto = $dadosBoleto->textoMensagemBloquetoTitulo;

                $carne->mensagemBloqueto .= '<br>' . 'Pagável em qualquer banco até o vencimento';
                $carne->mensagemBloqueto .= '<br>' . $descBoletoMulta;
                $carne->mensagemBloqueto .= '<br>' . $descBoletoMora;
                $carne->mensagemBloqueto .= '<br>' . $descBoletoProtesto;

                $carne->nossoNumero = $dadosBoleto->nossoNumero;
                $carne->nomeBanco = 'Banco do Brasil';
                $carne->codigoBanco = '001-9';
                $carne->dataVencimento = $this->converterData($dadosBoleto->dataVencimentoTituloCobranca, $formatoAtual = 'd.m.Y', $novoFormato = 'd/m/Y');
                $carne->valorDocumento = $dadosBoleto->valorAtualTituloCobranca;
                $carne->numeroDocumento = $dadosBoleto->numeroTituloCedenteCobranca;
                $carne->nomePagador = $dadosBoleto->nomeSacadoCobranca;
                $carne->CNPJCPFPagador = '';
                $carne->CEPPagador = $dadosBoleto->numeroCepSacadoCobranca;
                $carne->enderecoPagador = $dadosBoleto->textoEnderecoSacadoCobranca;
                $carne->bairroPagador = $dadosBoleto->nomeBairroSacadoCobranca;
                $carne->cidadePagador = $dadosBoleto->nomeMunicipioSacadoCobranca;
                $carne->UFPagador = $dadosBoleto->siglaUnidadeFederacaoSacadoCobranca;
                $carne->nomeEmpresa = $dadosBoleto->nomeSacadorAvalistaTitulo;
                $carne->CNPJEmpresa = $this->formatarCPFCNPJ($dadosBoleto->numeroInscricaoSacadorAvalista);
                $carne->agencia = $dadosBoleto->agencia;
                $carne->codigoCedente = $dadosBoleto->contaCorrente;
                $carne->codigoAceite = $dadosBoleto->codigoAceiteTituloCobranca;
                $carne->dataEmissao = $this->converterData($dadosBoleto->dataEmissaoTituloCobranca, $formatoAtual = 'd.m.Y', $novoFormato = 'd/m/Y');
                $carne->carteiraCobranca = $dadosBoleto->numeroCarteiraCobranca;
                $carne->boletoPago = $carne->valorDocumento <= $dadosBoleto->valorPagoSacado;

                break;

            default:

                exit('Carnê indisponível para esse banco');
                break;
        }



        return $carne;
    }

    private function converterData($data, $formatoAtual, $novoFormato) {
        $dataObj = DateTime::createFromFormat($formatoAtual, $data);
        return $dataObj->format($novoFormato);
    }

    private function formatarCPFCNPJ($documento) {
        $documento = preg_replace('/[^0-9]/', '', $documento);

        if (strlen($documento) == 11) { // CPF
            return substr($documento, 0, 3) . '.' . substr($documento, 3, 3) . '.' . substr($documento, 6, 3) . '-' . substr($documento, 9, 2);
        } elseif (strlen($documento) == 14) { // CNPJ
            return substr($documento, 0, 2) . '.' . substr($documento, 2, 3) . '.' . substr($documento, 5, 3) . '/' . substr($documento, 8, 4) . '-' . substr($documento, 12, 2);
        } else {
            return $documento;
        }
    }

}
