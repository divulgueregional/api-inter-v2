<?php
require_once 'CSS.php';

use Picqer\Barcode\BarcodeGeneratorDynamicHTML;

$generator = new BarcodeGeneratorDynamicHTML();
echo '<title>Carnê bancário</title>';
$qtdBoletos = count($boletos);
$boletoAtual = 0;
foreach ($boletos as $dadosBoleto) {


    $barcode = $generator->getBarcode($dadosBoleto->codigoBarras, BarcodeGeneratorDynamicHTML::TYPE_INTERLEAVED_2_5);
    $dadosBoleto->codBarrasHTML = $barcode;
    $boletoAtual++;
    ?>

    <table>
        <tr>
            <td style="width:200px" class="alinhar_top">
                <div>
                    <?= viaCliente($dadosBoleto) ?>
                </div>            
            </td>


            <td style="width:10px;" >
                <!--<div style="border-left:1px dashed; height: 330px;margin-left: 5px;"></div>-->
            </td>


            <td style="width: 545px">
                <div>
                    <?= viaBanco($dadosBoleto) ?>
                </div>
            </td>
        </tr>
    </table>

    <?php
    $multiploDe3 = $boletoAtual % 3 == 0;

    if ($qtdBoletos > 1 && $boletoAtual != $qtdBoletos && !$multiploDe3) {
        ?>
        <br>
        <div style="border-bottom:1px dashed; height: 1px"></div>
        <br>
        <?php
    }

    if ($multiploDe3 && $boletoAtual != $qtdBoletos) {
        ?>
        <span style="page-break-before: always;"></span>
    <?php } ?>


    <?php
}

function viaBanco($dadosBoleto) {
    ?>

    <div class="boleto">
        <!--Bloco A-->
        <table >
            <tr>
                <td style="width: 120px" class="borda_direta font_12">
                    <b><?= $dadosBoleto->nomeBanco ?? '' ?></b>
                </td>
                <td style="width: 50px" class="borda_direta alinhar_centro  font_12">
                    <b><?= $dadosBoleto->codigoBanco ?? '' ?></b>
                </td>
                <td class="alinhar_direta  font_12">
                    <b><?= ($dadosBoleto->codigoBarrasDigitavel ?? '') ?></b>
                </td>
            </tr>
        </table>
        <!--Fim Bloco A-->



        <!--Bloco B-->
        <table class="table_bloco">
            <tr >
                <td style="width: 70%" >
                    <div class="div_campo_info altura_fixa_28">
                        <div class="titulo_campo ">Local de pagamento</div>
                        <div class="info_campo">Pagável em qualquer Banco até o vencimento</div>
                    </div>
                </td>
                <td style="width: 30%;" >
                    <div class="div_campo_info altura_fixa_28">
                        <div class="titulo_campo ">Vencimento</div>
                        <div class="info_campo"><?= ($dadosBoleto->dataVencimento ?? '') ?></div>
                    </div>
                </td>

            </tr>
        </table>
        <!--Fim Bloco B-->



        <!--Bloco C-->
        <table class="table_bloco" >
            <tr  >
                <td style="width: 70%" >
                    <div class="div_campo_info altura_fixa_28">
                        <div class="titulo_campo ">Cedente</div>
                        <div class="info_campo"><?= ($dadosBoleto->nomeEmpresa ?? '') ?></div>
                    </div>
                </td>
                <td style="width: 30%;"  >
                    <div class="div_campo_info altura_fixa_28">
                        <div class="titulo_campo ">Agência/Código do cedente </div>
                        <div class="info_campo"><?= ($dadosBoleto->agencia ?? '') ?>/<?= ($dadosBoleto->codigoCedente ?? '') ?></div>
                    </div>
                </td>
            </tr>
        </table>
        <!--Fim Bloco C-->


        <!--Bloco D-->
        <table class="table_bloco" >
            <tr  >
                <td style="width: 15%" >
                    <div class="div_campo_info altura_fixa_28">
                        <div class="titulo_campo ">Data do documento</div>
                        <div class="info_campo"><?= ($dadosBoleto->dataEmissaoTituloCobranca ?? '') ?></div>
                    </div>
                </td>
                <td style="width: 15%;"  >
                    <div class="div_campo_info altura_fixa_28">
                        <div class="titulo_campo ">Nº do documento </div>
                        <div class="info_campo"><?= $dadosBoleto->numeroDocumento ?? '' ?></div>
                    </div>
                </td>

                <td style="width: 12%;"  >
                    <div class="div_campo_info altura_fixa_28">
                        <div class="titulo_campo ">Espécie DOC </div>
                        <div class="info_campo">DM</div>
                    </div>
                </td>

                <td style="width: 9.36%;"  >
                    <div class="div_campo_info altura_fixa_28">
                        <div class="titulo_campo ">Aceite </div>
                        <div class="info_campo"><?= ($dadosBoleto->codigoAceite ?? '') ?></div>
                    </div>
                </td>

                <td style="width: 13.74%;"  >
                    <div class="div_campo_info altura_fixa_28">
                        <div class="titulo_campo ">Data process. </div>
                        <div class="info_campo"><?= ($dadosBoleto->dataEmissao ?? '') ?></div>
                    </div>
                </td>
                <td style="width: 27.9%;"  >
                    <div class="div_campo_info altura_fixa_28">
                        <div class="titulo_campo ">Nosso número </div>
                        <div class="info_campo"><?= ($dadosBoleto->nossoNumero ?? '') ?></div>
                    </div>
                </td>
            </tr>
        </table>
        <!--Fim Bloco D-->






        <!--Bloco E-->
        <table class="table_bloco" >
            <tr  >
                <td style="width: 16.1%" >
                    <div class="div_campo_info altura_fixa_28">
                        <div class="titulo_campo ">Uso do Banco</div>
                        <div class="info_campo"> </div>
                    </div>
                </td>
                <td style="width: 16.15%;"  >
                    <div class="div_campo_info altura_fixa_28">
                        <div class="titulo_campo ">Carteira</div>
                        <div class="info_campo"><?= ($dadosBoleto->carteiraCobranca ?? '') ?></div>
                    </div>
                </td>

                <td style="width: 12.9%;"  >
                    <div class="div_campo_info altura_fixa_28">
                        <div class="titulo_campo ">Espécie</div>
                        <div class="info_campo">R$</div>
                    </div>
                </td>

                <td style="width: 10.1%;"  >
                    <div class="div_campo_info altura_fixa_28">
                        <div class="titulo_campo ">Quantidade</div>
                        <div class="info_campo"> </div>
                    </div>
                </td>

                <td style="width: 14.758%;"  >
                    <div class="div_campo_info altura_fixa_28">
                        <div class="titulo_campo ">Valor</div>
                        <div class="info_campo"></div>
                    </div>
                </td>
                <td style="width: 0%;"  >
                    <div class="div_campo_info altura_fixa_28">
                        <div class="titulo_campo ">(=) Valor do documento</div>
                        <div class="info_campo">R$ <?= number_format($dadosBoleto->valorDocumento ?? '0', 2, ',', '.') ?></div>
                    </div>
                </td>
            </tr>
        </table>
        <!--Fim Bloco E-->


        <table style="border-collapse:unset; border: none" >
            <tr>
                <td style="width: 69.87%;"  >

                    <!--Instruções do boleto-->
                    <table class="table_bloco" style="border-collapse:unset" >
                        <tr class="alinhar_top" >
                            <td style="height: 85px;" >
                                <div class="titulo_campo ">Instruções de responsabilidade do beneficiário. Qualquer dúvida, contate o beneficiário</div>
                                <div style="height: 80px;overflow: hidden;" class="info_campo font_10">
                                    <?= $dadosBoleto->mensagemBloqueto ?>
                                </div>
                            </td>
                        </tr>
                    </table>
                    <!-- Fim Instruções do boleto-->

                </td>
                <td style="width: 0%;" class="alinhar_top" >

                    <!--Bloco F-->
                    <table class="table_bloco border_collapse" >
                        <tr>
                            <td>
                                <div class="div_campo_info altura_fixa_28">
                                    <div class="titulo_campo ">(-) Desconto/Abatimento</div>
                                    <div class="info_campo"> </div>
                                </div>
                            </td>
                        </tr>
                    </table>
                    <!--Fim Bloco F-->





                    <!--Bloco G-->
                    <table class="table_bloco border_collapse" >
                        <tr>
                            <td >
                                <div class="div_campo_info altura_fixa_28">
                                    <div class="titulo_campo ">(+) Juros/Multa</div>
                                    <div class="info_campo"></div>
                                </div>
                            </td>
                        </tr>
                    </table>
                    <!--Fim Bloco G-->



                    <!--Bloco H-->
                    <table class="table_bloco border_collapse" >
                        <tr>
                            <td >
                                <div class="div_campo_info altura_fixa_28">
                                    <div class="titulo_campo ">(=) Valor cobrado</div>
                                    <div class="info_campo">R$ <?= number_format($dadosBoleto->valorDocumento ?? '0', 2, ',', '.') ?></div>
                                </div>
                            </td>
                        </tr>
                    </table>
                    <!--Fim Bloco H-->

                </td>
            </tr>
        </table>


        <!--Bloco I-->
        <table class="table_bloco" >
            <tr  >
                <td style="width: 100%" >
                    <div class="div_campo_info font_10">
                        <div class="titulo_campo ">Sacado</div>
                        <div class="info_campo"><?= $dadosBoleto->nomePagador ?? '' ?></div>
                        <div class="info_campo"><?= ($dadosBoleto->CEPPagador ?? '') ?>, <?= ($dadosBoleto->bairroPagador ?? '') ?>, <?= ($dadosBoleto->cidadePagador ?? '') ?>-<?= ($dadosBoleto->UFPagador ?? '') ?></div>
                    </div>
                </td>

            </tr>
        </table>
        <!--Fim Bloco I-->


        <!--Bloco J-->
        <div class="titulo_campo  alinhar_direta">Autenticação mecânica</div>
        <div style="height: 35px;max-height: 35px;overflow: hidden; text-align: center">
            <?php
            if ($dadosBoleto->boletoPago) {
                echo "Essa parcela já foi paga.";
            } else {
                echo str_replace('position:relative;width:100%;height:100%', 'position:relative;width:100%;height:35px', $dadosBoleto->codBarrasHTML);
            }
            ?>
        </div>
        <!--Fim Bloco J-->


    </div>
    <?php
}

function viaCliente($dadosBoleto) {
    ?>

    <div class="boleto">


        <table >
            <tr>
                <td style="width: 120px" class="borda_direta font_12">
                    <b><?= $dadosBoleto->nomeBanco ?? '' ?></b>
                </td>
                <td style="width: 50px" class=" alinhar_centro  font_12">
                    <b><?= $dadosBoleto->codigoBanco ?? '' ?></b>
                </td>

            </tr>
        </table>


        <table style="border-collapse:unset; border: none" >
            <tr>

                <td style="width: 0%;" class="alinhar_top" >

                    <table class="table_bloco border_collapse" >
                        <tr>
                            <td>
                                <div class="div_campo_info altura_fixa_28">
                                    <div class="titulo_campo ">Nosso número</div>
                                    <div class="info_campo"><?= ($dadosBoleto->nossoNumero ?? '') ?></div>
                                </div>
                            </td>
                        </tr>
                    </table>

                    <table class="table_bloco border_collapse" >
                        <tr>
                            <td>
                                <div class="div_campo_info altura_fixa_28">
                                    <div class="titulo_campo ">Vencimento</div>
                                    <div class="info_campo"><?= ($dadosBoleto->dataVencimento ?? '') ?></div>
                                </div>
                            </td>
                        </tr>
                    </table>


                    <table class="table_bloco border_collapse" >
                        <tr>
                            <td>
                                <div class="div_campo_info altura_fixa_28">
                                    <div class="titulo_campo ">Valor do documento</div>
                                    <div class="info_campo">R$ <?= number_format($dadosBoleto->valorDocumento ?? '0', 2, ',', '.') ?></div>
                                </div>
                            </td>
                        </tr>
                    </table>

                    <table class="table_bloco border_collapse" >
                        <tr>
                            <td>
                                <div class="div_campo_info altura_fixa_28">
                                    <div class="titulo_campo ">(-) Desconto/Abatimento</div>
                                    <div class="info_campo"> </div>
                                </div>
                            </td>
                        </tr>
                    </table>

                    <table class="table_bloco border_collapse" >
                        <tr>
                            <td >
                                <div class="div_campo_info altura_fixa_28">
                                    <div class="titulo_campo ">(+) Juros/Multa</div>
                                    <div class="info_campo"></div>
                                </div>
                            </td>
                        </tr>
                    </table>


                    <table class="table_bloco border_collapse" >
                        <tr>
                            <td >
                                <div class="div_campo_info altura_fixa_28">
                                    <div class="titulo_campo ">Nº do documento</div>
                                    <div class="info_campo"><?= $dadosBoleto->numeroDocumento ?></div>
                                </div>
                            </td>
                        </tr>
                    </table>

                    <table class="table_bloco border_collapse" >
                        <tr>
                            <td >
                                <div class="div_campo_info altura_fixa_28">
                                    <div class="titulo_campo ">(=) Valor cobrado</div>
                                    <div class="info_campo">R$ <?= number_format($dadosBoleto->valorDocumento ?? '0', 2, ',', '.') ?></div>
                                </div>
                            </td>
                        </tr>
                    </table>

                    <table class="table_bloco border_collapse" >
                        <tr>
                            <td >
                                <div class="div_campo_info altura_fixa_28">
                                    <div class="titulo_campo ">Pagador</div>
                                    <div class="info_campo font_8"><?= $dadosBoleto->nomePagador ?? '' ?></div>
                                </div>
                            </td>
                        </tr>
                    </table>

                </td>
            </tr>
        </table>

    </div>
    <div class="font_12 font_pad">
        <?= $dadosBoleto->nomeEmpresa ?? '' ?><br>
        CNPJ: <?= $dadosBoleto->CNPJEmpresa ?? '' ?>

    </div>
    <?php
}
