<!-- <div class="section-block"> -->
    <div class="row pt-0">
        <div class="column width-12 pt-0">
            <table class="table striped">
                <thead>
                    <tr class="bkg-charcoal color-white text-medium small center">
                        <th>Evento</th>
                        <th>Inicio</th>
                        <th>Término</th>
                        <th>Valor (R$)</th>
                        <th>Acesso</th>
                    </tr>
                </thead>
                <tbody class="text-medium color-charcoal"><?php    
                            if(!isset($_GET['ms']) OR empty($_GET['ms'])):
                                $selectmes="";
                            elseif(isset($_GET['ms']) AND !empty($_GET['ms'])):
                                $selectmes=" datadoinicio LIKE '%$mesdoevento$' AND";
                            endif;

                            //AQUI ELE FAZ A PESQUISA PELO CAMPUS E TRAZ O DADO MAIS RECENTE PELO ANO, SE ESTAMOS EM 2010 ELE TRARÁ OS DADOS DESTE ANO OU DO ÚLTIMO ANO EM QUE O CAMPUS APRESENTOU UMA MATÉRIA REGISTRADA, COM BASE NISSO É FEITO UM FILTRO EXIBINDO POR ORDEM O DIA E DEPOIS O HORÁRIO.
                            if(!isset($_SESSION['membro'])):
                                if(isset($_GET['ms']) AND !empty($_GET['ms'])):
                                    $bMais = "SELECT video, miniatura, evento, codigodoevento, datadoinicio, horariodeinicio, datadotermino, horariodetermino, valor, visibilidade FROM agenda WHERE datadoinicio LIKE '%$mesdoevento%' AND ano='$ano' AND visibilidade='Externo' GROUP BY codigodoevento ORDER BY datadoinicio ASC";
                                else:
                                    $bMais = "SELECT video, miniatura, evento, codigodoevento, datadoinicio, horariodeinicio, datadotermino, horariodetermino, valor, visibilidade FROM agenda WHERE ano='$ano' AND visibilidade='Externo' GROUP BY codigodoevento ORDER BY datadoinicio ASC";
                                endif;
                            else:
                                if(isset($_GET['ms']) AND !empty($_GET['ms'])):
                                    $bMais = "SELECT video, miniatura, evento, codigodoevento, datadoinicio, horariodeinicio, datadotermino, horariodetermino, valor, visibilidade FROM agenda WHERE datadoinicio LIKE '%$mesdoevento%' AND ano='$ano' GROUP BY codigodoevento ORDER BY datadoinicio ASC";
                                else:
                                    $bMais = "SELECT video, miniatura, evento, codigodoevento, datadoinicio, horariodeinicio, datadotermino, horariodetermino, valor, visibilidade FROM agenda WHERE ano='$ano' GROUP BY codigodoevento ORDER BY datadoinicio ASC";
                                endif;
                            endif;
                                $rMais = mysqli_query($con, $bMais);
                                        $gridItem = 0;
                                    while($dMais = mysqli_fetch_array($rMais)):
                                        $gridItem++;											
                                $Msgs++;
                                
                                // $SlideDoPost            = $dMais['slidedeminis'];
                                $miniaturasDoPost[]     = $dMais['miniatura'];
                                $refDoPost              = $dMais['codigodoevento'];
                                $titulodopost           = $dMais['evento'];
                                $titulo 	            = str_replace(' ', '-', $titulodopost);
                                $datadoinicio=$dMais['datadoinicio'];
                                $datadoinicio = date(('d/m/Y'), strtotime($datadoinicio));
                                $datadotermino=$dMais['datadotermino'];
                                $datadotermino = date(('d/m/Y'), strtotime($datadotermino));
                        ?>
                    <tr>
                        
                        <td><?php if($dMais['evento'] == '1º dia - Aniversário da Emanuel' OR $dMais['evento'] == '2º dia - Aniversário da Emanuel'): echo "<strong>".$dMais['evento']."</strong>"; else: echo $dMais['evento']; endif;?></td>
                        <td><?php echo $datadoinicio.'; '.$dMais['horariodeinicio'].'h';?></td>
                        <td><?php echo $datadotermino.'; '.$dMais['horariodetermino'].'h';?></td>
                        <td>R$ <?php echo $dMais['valor'];?></td>
                        <td><?php if($dMais['visibilidade'] === 'Externo'): echo 'Aberto'; elseif($dMais['visibilidade'] === 'Interno'): echo 'Exclusivo para membros'; endif;?></td>
                    </tr>
                        <?php endwhile;?>
                </tbody>
            </table>
        </div>
    </div>
<!-- </div> -->