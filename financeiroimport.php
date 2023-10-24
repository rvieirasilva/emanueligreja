<!DOCTYPE html>
<?php
	session_start();
	if(!empty($_GET['lng'])):
		$idioma = $_GET['lng'];
	elseif(empty($_GET['lng']) OR !isset($_GET['lng'])):
		$idioma = 'pt-BR';
    endif;
    
    include "./_con.php";
    include "./_configuracao.php";

    //var_dump($_SESSION);
    if(isset($_SESSION["lideranca"])):
        include "protectcolaborador.php";	
    elseif(isset($_SESSION["membro"])):
        include "protectusuario.php";
    else:
        session_destroy();
        header("location:index");
	endif;    

    // if(isset($_GET['ref'])):
    //     $ideditado=$_GET['ref'];
    // endif;

    // if(isset($_POST['btn-csv'])):        
    //     if($arquivo['type'] !== "text/csv"):
    //         $_SESSION['cordamensagem']="red";
    //         $_SESSION['mensagem']="Este arquivo não é válido. Envie um arquivo CSV.";
    //         header("refresh:3");
    //     endif;
    // endif;

    if(isset($_POST['btn-upload'])):
        if(!empty($_POST['valor'])):
            for ($mov=0; $mov < count($_POST['valor']); $mov++) { 
                $data = mysqli_escape_string($con, $_POST['data'][$mov]);
                    if(empty($data)): $data=date('Y-m-d'); endif;
                $valor = mysqli_escape_string($con, $_POST['valor'][$mov]);
                $codigodaoperacao = mysqli_escape_string($con, $_POST['codigodaoperacao'][$mov]);
                $recebidode = mysqli_escape_string($con, $_POST['recebidode'][$mov]);
                $recebidode2 = mysqli_escape_string($con, $_POST['recebidode2'][$mov]);
                $categoria = mysqli_escape_string($con, $_POST['categoria'][$mov]);
                $categoria2 = mysqli_escape_string($con, $_POST['categoria2'][$mov]);
                $descricao = mysqli_escape_string($con, $_POST['descricao'][$mov]); $descricao= strtolower($descricao); $descricao=ucwords($descricao);
                $conta = mysqli_escape_string($con, $_POST['conta'][$mov]);

                if(!empty($recebidode2)): //Se o usuário inseriu um nome no campo Recebido2
                    $recebidode = $recebidode2;
                endif;   
                if(!empty($categoria2)): //Se o usuário inseriu um nome no campo Recebido2
                    $categoria = $categoria2;
                endif;
                    $categoria = strtolower($categoria); //Coloca a primeira em maiúscula
                    $categoria = ucwords($categoria); //Coloca a primeira em maiúscula

                $bCat="SELECT id FROM categoriasfinanceiro WHERE categoria='$categoria'";
                  $rCatN=mysqli_query($con, $bCat);
                    $nCat=mysqli_num_rows($rCatN);
                
                if($nCat < 1):
                    $idCat="SELECT id FROM categoriasfinanceiro";
                      $rIDCat=mysqli_query($con, $idCat);
                        $nIDC=mysqli_num_rows($rIDCat);
                        
                    $codigodacategoria=$nIDC.mt_rand(000,999);
                    if($valor < 1):
                        $tipodecategoria = 'DÉBITO';
                    else:
                        $tipodecategoria = 'CRÉDITO';
                    endif;
                    $inCat="INSERT INTO categoriasfinanceiro (empresa, codigodoparceiro, urldaempresa, dia, mes, ano, representante, codigodorepresentante, codigodacategoria, categoria, tipodecategoria) VALUES ('Emanuel', '0001', 'igrejaemanuel', '$dia', '$mes', '$ano', '$nomedousuario', '$matricula', '$codigodacategoria', '$categoria', '$tipodecategoria')";
                    mysqli_query($con, $inCat);
                endif;

                if(empty($codigodaoperacao)):
                    $bCF="SELECT id FROM financeiro ORDER BY id DESC LIMIT 1";
                      $rCF=mysqli_query($con, $bCF);
                        $dCF=mysqli_fetch_array($rCF);
                    $codigodaoperacao=substr($dCF['id'], 0, -4).date('ymd').mt_rand(0000,9999);
                endif;

                //Criptografia 1
                $recebidode = base64_encode($recebidode);
                $descricao  = base64_encode($descricao);

                //Criptografia 2
                $recebidode = base64_encode($recebidode);
                $descricao  = base64_encode($descricao);
                
                $dataprevistaSEP = explode('-', $data); // 2020 - 03 - 30
                $anoprevisto     = $dataprevistaSEP[0]; // 2020
                $mesprevisto     = $dataprevistaSEP[1]; // 
                $diaprevisto     = $dataprevistaSEP[2]; // 
                
                    //Adequa o valor para BD
                if($valor > 0):
                    if($valor >= '1.000'): //Se o valor inserido for maior que 1000 utiliza o algoritimo abaixo.
                        @$valor = explode('.', $valor); //Separa a milhar 1 099,60
                        @$valor  = $valor[0].$valor[1]; //Une o valor mantendo a virgula: 1099,60
                        @$valor  = str_replace(',','.',$valor); //Troca a virgula por ponto para as operações matemáticas do php
                    elseif($valor < '1.000'): //Se for inferior utiliza o algoritimo.
                        @$valor  = str_replace(',', '.', $valor);
                    endif;
                elseif($valor < 0):
                    if($valor >= '(1.000)'): //Se o valor inserido for maior que 1000 utiliza o algoritimo abaixo.
                        @$valor = explode('.', $valor); //Separa a milhar 1 099,60
                        @$valor  = $valor[0].$valor[1]; //Une o valor mantendo a virgula: 1099,60
                        @$valor  = str_replace(',','.',$valor); //Troca a virgula por ponto para as operações matemáticas do php
                    elseif($valor < '(1.000)'): //Se for inferior utiliza o algoritimo.
                        @$valor  = str_replace(',', '.', $valor);
                    endif;
                endif;
                
                if($valor > 0):
                    $bED = "SELECT id FROM financeiro WHERE descricao='$descricao' AND recebidode='$recebidode' AND valorprevisto='$valor' AND dataprevista='$data' AND tipoderegistro='CRÉDITO'";
                        $rBPD = mysqli_query($con, $bED);
                            $nBPD = mysqli_num_rows($rBPD);
                            
                    if($nBPD < 1):
                        $inserir="INSERT INTO financeiro (empresa, codigodoparceiro, urldaempresa, dia, mes, ano, representante, codigodorepresentante, codigodaoperacao, descricao, valorprevisto, dataprevista, valorrealizado, datarealizada, recebidode, pagopor, anexo, tamanhodoarquivo, tipodeoperacao, categoria, centrodecusto, conta1, conta2, tipoderegistro) VALUES ('Emanuel', '0001', 'igrejaemanuel', '$diaprevisto', '$mesprevisto', '$anoprevisto', '$nomedousuario', '$matriculausuario', '$codigodaoperacao', '$descricao', '$valor', '$data', '$valor', '$data', '$recebidode', '', '', '', 'Importada do arquivo CSV', '$categoria', '', '$conta', '', 'CRÉDITO')";
                            if(mysqli_query($con, $inserir)):
                                //Se inserir no BD move as imagens.
                                //move_uploaded_file($temporariominiatura, $pastaminiatura.$novoNomeminiatura);
        
                                $_SESSION['cordamensagem']="green";
                                $_SESSION['mensagem']="Receita <strong>cadastrada</strong> com sucesso.";
                                // header("refresh:1, url=financeiro$linkSeguro&type=$tipodeoperacaoget&t=$tipodeoperacaoget&ms=$mesdeoperacaoget&y=$yta2");
                            else:
                                // @unlink($anexo); //Exclui o arquivo que foi enviado anteriormente.
                                $_SESSION['cordamensagem']="red";
                                $_SESSION['mensagem']="<strong>Verifique sua conexão</strong>. Erro ao cadastrar receita.";
                            endif;                        
                    else:
                        $_SESSION['cordamensagem']="red";
                        $_SESSION['mensagem']="Esta movimentação foi registrada anteriormente.";
                    endif;
                elseif($valor < 0):
                    $valor =str_replace('-','',$valor); //Retira o sinal de negativo por causa dos gráficos, mas mantém como Débito
                    $bED = "SELECT id FROM financeiro WHERE descricao='$descricao' AND recebidode='$recebidode' AND valorprevisto='$valor' AND dataprevista='$data' AND tipoderegistro='DÉBITO'";
                    $rBPD = mysqli_query($con, $bED);
                        $nBPD = mysqli_num_rows($rBPD);
    
                    if($nBPD < 1): //Verifica duplicado
                        @$inserir="INSERT INTO financeiro (empresa, codigodoparceiro, urldaempresa, dia, mes, ano, representante, codigodorepresentante, codigodaoperacao, descricao, valorprevisto, dataprevista, valorrealizado, datarealizada, recebidode, pagopor, anexo, tamanhodoarquivo, tipodeoperacao, categoria, centrodecusto, conta1, conta2, tipoderegistro) VALUES ('Emanuel', '0001', 'igrejaemanuel', '$diaprevisto', '$mesprevisto', '$anoprevisto', '$nomedousuario', '$matriculausuario', '$codigodaoperacao', '$descricao', '$valor', '$data', '$valor', '$data', '$recebidode', '', '', '', '$tipodeoperacaoget', '$categoria', '', '$conta', '', 'DÉBITO')"; 
                            if(mysqli_query($con, $inserir)):
                                //Se inserir no BD move as imagens.
                                //move_uploaded_file($temporariominiatura, $pastaminiatura.$novoNomeminiatura);
        
                                $_SESSION['cordamensagem']="green";
                                $_SESSION['mensagem']="Débito <strong>cadastrada</strong> com sucesso.";
                                // header("refresh:1, url=financeiro$linkSeguro&type=$tipodeoperacaoget&t=$tipodeoperacaoget&ms=$mesdeoperacaoget&y=$yta2");
                            else:
                                @unlink($anexo); //Exclui o arquivo que foi enviado anteriormente.
                                $_SESSION['cordamensagem']="red";
                                $_SESSION['mensagem']="<strong>Verifique sua conexão</strong>. Erro ao cadastrar débito.";
                            endif;                        
                    else:
                        $_SESSION['cordamensagem']="red";
                        $_SESSION['mensagem']="Esta movimentação foi registrada anteriormente.";
                    endif;
                endif;
            }
        endif;
    endif;
    
    //Dados para registrar o log do cliente.
    $mensagem = ("\n$nomedocolaborador ($matricula) Acessou página financeira para carregamento de arquivo CSV. Isto através do IP: $ip, na data $dia às $hora.\n"); //Escreva a mensagem que será gravada no log.
        include "registrolog.php";
?>
<html lang="pt-BR">
<head>
	<? include "./_head.php"; ?>
</head>
<body class="shop blog home-page">

    <?php
        // if(isset($_SESSION["membro"])):
            include "./_menu.php";
        // endif;
	?>
			<!-- Content -->
			<div class="content clearfix">
                <div class="section-block tm-slider-parallax-container pb-30 small bkg-blue">
					<div class="row">
                        <div class="box rounded small bkg-white shadow">
						    <div class="column width-12">
                                <div class="title-container">
                                    <div class="title-container-inner">
                                        <div class="row flex">
                                            <div class="column width-12 v-align-middle">
                                                <div>
                                                    <h1 class="mb-0">Importar dados financeiros de um <strong>arquivo</strong></h1>
                                                    <p class="text-large color-charcoal mb-0 mb-mobile-20">Aqui realizamos o controle e o planejamento financeiro da instituição.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
							</div>
						</div>
					</div>
				</div>
                <div class="section-block team-2 pt-50 bkg-grey-ultralight">
                    <div class="row">
                        <?php include "./_notificacaomensagem.php"; ?>
                        <? if(!isset($_POST['btn-csv']) AND !isset($_POST['btn-bancario'])): ?>
                            <div class="box rounded rounded shadow border-blue bkg-white">
                                <div class="column width-12">
                                    <form charset="UTF-8" class="form" action="" method="post" enctype="Multipart/form-data">
                                        <div class="column width-12">
                                            <label class="text-large color-charcoal">Selecionar <strong>arquivo CSV</strong></label>
                                            <div class="field-wrapper">
                                                <input type="file"
                                                    name="anexo"
                                                    class="form-element rounded medium left"
                                                    placeholder="Substituir ou adicionar comprovante."
                                                    tabindex="001">
                                            </div>
                                        </div>
                                        
                                        <div class="column width-12">
                                            <button type="submit" value="" name='btn-csv' class="form-submit button rounded small bkg-blue bkg-hover-blue color-white color-hover-white hard-shadow">
                                                <span class="icon-plus color-white color-hover-white circled"></span> 
                                                <span class="text-medium">Carregar dados CSV do <strong>EXCELL</strong></span>
                                            </button>
                                            <button type="submit" value="" name='btn-bancario' class="form-submit button rounded small bkg-base bkg-hover-blue color-charcoal color-hover-white hard-shadow">
                                                <span class="icon-plus color-white color-hover-white circled"></span> 
                                                <span class="text-medium">Carregar dados CSV <strong>BANCÁRIO</strong></span>
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        <?
                            else:
                        ?>
                        
                        <div class="box small rounded rounded shadow border-blue bkg-white">
                            <a href="financeiroimport?<? echo $linkSeguro; ?>">Voltar</a>
                        </div>
                        <form charset="UTF-8" class="form" action="" method="post" enctype="Multipart/form-data">
                            <?
                                function converter (&$dadosDoArq){
                                    $dadosDoArq = mb_convert_encoding($dadosDoArq, "UTF-8", "ISO-8859-1");
                                }
                                $arquivo = $_FILES['anexo'];
                                $primeiralinha=true;
                                $dadosDoArq=fopen($arquivo['tmp_name'], "r");
                                while($linha=fgetcsv($dadosDoArq, 1000, ';')){

                                    array_walk_recursive($linha, 'converter');

                                    if($primeiralinha): $primeiralinha=false; continue; endif;
                                    $valline = $linha[1];
                                    $dataPT= $linha[0];
                                    $dataEN=implode("-", array_reverse(explode("/", $dataPT)));
                                    if($valline < 0 or $valline <= 0):
                                        $cordocard='red';
                                    else:
                                        $cordocard='blue';
                                    endif;

                                if(isset($_POST['btn-bancario'])):
                                    if($valline > 0) :
                                        if($valline >= 1000): //Se o valor inserido for maior que 1000 utiliza o algoritimo abaixo.
                                            @$valPT = number_format($valline, 2, '.', ',');
                                        elseif($valline < 1000): //Se for inferior utiliza o algoritimo.
                                            @$valPT = number_format($valline, 2, ',', ',');
                                        endif;
                                    elseif($valline <= 0) :
                                        if($valline < (-1000)): //Se o valor inserido for maior que 1000 utiliza o algoritimo abaixo.
                                            @$valPT = number_format($valline, 2, ',', '.');
                                        elseif($valline >= (-1000)): //Se for inferior utiliza o algoritimo.
                                            @$valPT = str_replace('.', ',', $valline);
                                        endif;
                                    endif;
                                else:
                                    $valPT=$valline;
                                endif;
                            ?>
                            <div class="box small dismissable rounded rounded shadow border-<? echo $cordocard; ?> bkg-white">
                                <div class="row">
                                    <div class="column width-2">
                                        <label class="text-large color-charcoal"><strong>Data</strong> da operação.</label> 
                                        <div class="field-wrapper"> <input type="date" name="data[]" maxlength="15" tabindex='001' value="<? if(!empty($linha[0])): $dateimp=$linha[0]; $dateimp=str_replace('/', '-', $dateimp); $dateimp=date(('Y-m-d'), strtotime($dateimp)); echo $dateimp; else: echo $dataEN; endif;?>" class="form-fname form-element rounded small" placeholder=""></div>
                                    </div>
                                    <div class="column width-2">
                                        <label class="text-large color-charcoal"><strong>Valor</strong> (R$)</label> 
                                        <div class="field-wrapper"> <input type="text" name="valor[]" maxlength="20" tabindex='001' value="<? echo $valPT?>" class="form-fname form-element rounded small" placeholder=""></div>
                                    </div>
                                    <div class="column width-2">
                                        <label class="text-large color-charcoal"><strong>Código da operação</strong></label> 
                                        <div class="field-wrapper"> <input type="text" name="codigodaoperacao[]" maxlength="20" tabindex='001' value="<? echo $linha[2]?>"  class="form-fname form-element rounded small" placeholder=""></div>
                                    </div>
                                    <div class="column width-2">
                                        <label class="text-large color-charcoal"><strong>Pago ao</strong></label>
                                        <div class="form-select form-element rounded small">
                                            <select name="recebidode[]" tabindex="058" class="form-aux" data-label="Project Budget">
                                                <option selected="selected">Oferta em dinheiro</option>
                                                <option>Oferta em dinheiro</option>
                                                <option>Oferta em PIX</option>
                                                <option>Oferta em Cartão (Crédito)</option>
                                                <option>Oferta em Cartão (Débito)</option>
                                                <option>Oferta da célula Abraão</option>
                                                <option>Oferta da célula Efraim</option>
                                                <option>Retiro</option>
                                                <option>Parceiro Fiel - Constante</option>
                                                <option>Parceiro Fiel - Tito</option>
                                                <option>Parceiro Fiel - Coríntios</option>
                                                <option>Indefinido</option>
                                                <?php
                                                    //Buscar consumidores cadastrados
                                                    $bRD="SELECT nome, sobrenome FROM membros WHERE igreja='Emanuel' ORDER BY nome asc";
                                                    $rRD=mysqli_query($con, $bRD);
                                                    while($dRD=mysqli_fetch_array($rRD)):
                                                ?>
                                                <option>
                                                    <?php $nomeM = base64_decode($dRD['nome']); $nomeM=base64_decode($nomeM); $sobrenomeM = base64_decode($dRD['sobrenome']); $sobrenomeM=base64_decode($sobrenomeM); echo $nomeM.' '.$sobrenomeM;?>
                                                </option>
                                                <?php
                                                    endwhile;
                                                ?>
                                                <?php
                                                    //Buscar consumidores cadastrados
                                                    $bRD2="SELECT recebidode FROM financeiro WHERE empresa='Emanuel' GROUP BY recebidode ORDER BY recebidode ASC";
                                                    $rRD2=mysqli_query($con, $bRD2);
                                                    while($dRD2=mysqli_fetch_array($rRD2)):
                                                ?>
                                                <option>
                                                    <?php $nomeM2 = base64_decode($dRD2['recebidode']); $nomeM2=base64_decode($nomeM2); echo $nomeM2;?>
                                                </option>
                                                <?php
                                                    endwhile;
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="column width-2">
                                        <label class="text-large color-charcoal"><strong>Pago para</strong> (Adicionar)</label>
                                        <div class="field-wrapper">
                                            <input type="text" name="recebidode2[]" class="form-email form-element rounded small" tabindex="059">
                                        </div>
                                    </div>
                                    <div class="column width-2">
                                        <label
                                            class="text-large color-charcoal"><strong>Conta.</strong></label>
                                        <div
                                            class="form-select form-element rounded small">
                                            <select name="conta[]" tabindex="063" class="form-aux" data-label="Project Budget">
                                                <?php
                                                    $contaimportadabusca=$linha[4];
                                                    $bContasE="SELECT id, nomedaconta, conta FROM contasfinanceiro WHERE empresa='emanuel' AND urldaempresa='igrejaemanuel' AND nomedaconta LIKE '%$contaimportadabusca%'";
                                                    $rFCe=mysqli_query($con, $bContasE);
                                                    $dFCe=mysqli_fetch_array($rFCe);
                                                    if(!empty($dFCe['nomedaconta'])): $nameconta=$dFCe['nomedaconta']; $valconta=$dFCe['conta']; else: $nameconta=$linha[4]; $valconta=$linha[4]; endif;
                                                ?>
                                                <option value="<?php echo $valconta;?>"><?php echo $nameconta.' ('.$valconta.')';?></option>
                                                <?php
                                                    $bContas="SELECT id, nomedaconta, conta FROM contasfinanceiro WHERE empresa='Emanuel' AND urldaempresa='igrejaemanuel' AND conta !='$valconta'";
                                                    $rFC=mysqli_query($con, $bContas);
                                                    while($dFC=mysqli_fetch_array($rFC)):
                                                ?>
                                                <option value="<?php echo $dFC['conta'];?>">
                                                    <?php echo $dFC['nomedaconta'].' ('.$dFC['conta'].')';?>
                                                </option>
                                                <?php
                                                    endwhile;
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="column width-2">
                                        <label
                                            class="text-large color-charcoal"><strong>Categoria</strong></label>
                                        <div
                                            class="form-select form-element rounded small">
                                            <select name="categoria[]" tabindex="060" class="form-aux" data-label="Project Budget">
                                                <option selected="selected" value="<?php echo ($linha[5] ?? null);?>"><?php echo ($linha[5] ?? null);?></option>
                                                <?php
                                                    $exibircategorias="SELECT categoria FROM categoriasfinanceiro WHERE empresa='Emanuel' AND urldaempresa='igrejaemanuel' GROUP BY categoria ORDER BY categoria ASC";
                                                        $rc=mysqli_query($con, $exibircategorias);
                                                            while($dc=mysqli_fetch_array($rc)):
                                                ?>
                                                <option>
                                                    <?php echo $dc['categoria'];?>
                                                </option>
                                                <?php
                                                    endwhile;
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="column width-2">
                                        <label class="text-large color-charcoal"><strong>Categoria</strong> (Nova)</label>
                                        <div class="field-wrapper">
                                            <input type="text" name="categoria2[]" class="form-email form-element rounded small" tabindex="059">
                                        </div>
                                    </div>
                                    <div class="column width-8">
                                        <label class="text-large color-charcoal"><strong>Descrição</strong></label> 
                                        <div class="field-wrapper"> <input type="text" name="descricao[]" maxlength="200" tabindex='001' value="<? echo $linha[3]?>"  class="form-fname form-element rounded small" placeholder=""></div>
                                    </div>
                                </div>
                            </div>
                            <?
                                }
                            ?>
                            <div class="box rounded rounded shadow border-blue bkg-white">
                                <div class="column width-12">
                                    <button type="submit" name='btn-upload' class="column full-width button  medium bkg-blue bkg-hover-blue color-white color-hover-white">
                                        <span class="text-large weight-bold">
                                            Confirmar dados e salvar
                                        </span>
                                    </button>
                                </div>
                            </div>
                        </form>
                            <? endif; ?>
                    </div>
                </div>
			</div>
			<!-- Content End -->

			<!-- Footer -->
			<footer class="footer footer-blue">
				<? include "./_footer_top.php"; ?>
			</footer>
			<!-- Footer End -->

		</div>
	</div>

	<? include "./_script.php"; ?>
</body>
</html>