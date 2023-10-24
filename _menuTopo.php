<div class="column width-4 nav-bar-inner v-align-middle left">
    <div>
        <ul class="social-list list-horizontal">
            <li class="color-white color-hover-white shadow">
                <a href="precadastrodemembro" class="text-medium label bkg-blue-light">
                    Quero ser membro
                </a>
            </li>
            <?php
                include "./_midiassociais.php";
            ?>
        </ul>
    </div>
</div>
<div class="column width-8 nav-bar-inner v-align-middle right">
    <div class="column width-4 pt-0 pb-0">
        <!-- <form class="" action="" method="post" charset="utf-8" enctype="multipart/form-data">
            <div class="column width-10">
                <div class="form-select form-element small login-form-textfield">
                    <select name="localidade" tabindex="4" class="form-aux" data-label="vencimento">
                        <option selected="selected">Visitante</option>
                        <option>RJ - D. Caxias / Vila São Luiz</option>
                        <!- <option>RJ - D. Caxias / Laureano</option> ->
                    </select>
                </div>
            </div>
            <div class="column width-2">
                <button type="submit" class="button rounded small bkg-blue bkg-hover-blue color-white color-hover-white">
                    IR
                </button>
            </div>
        </form> -->
    </div>
    
    <?php
        // //require_once "_slidedoblog.php";
        // //AQUI ELE FAZ A PESQUISA PELO CAMPUS E TRAZ O DADO MAIS RECENTE PELO ANO, SE ESTAMOS EM 2010 ELE TRARÁ OS DADOS DESTE ANO OU DO ÚLTIMO ANO EM QUE O CAMPUS APRESENTOU UMA MATÉRIA REGISTRADA, COM BASE NISSO É FEITO UM FILTRO EXIBINDO POR ORDEM O DIA E DEPOIS O HORÁRIO.
        // $FraseTopo= "SELECT conceito, autor FROM conceitos WHERE NOT (postadoem > '$dataeng') AND conceito != '' ORDER BY rand(), postadoem DESC";
        //     $rFraseTopo = mysqli_query($con, $FraseTopo);
        //         $dFtop = mysqli_fetch_array($rFraseTopo);
    ?>
    <!-- <div class="column width-8 pt-0 pb-0"> -->
        <!-- <span class="text-small"><strong><?//php echo $dFtop['conceito'].' ('.$dFtop['autor'].')';?></strong>.</span> -->
    <!-- </div> -->
</div>
