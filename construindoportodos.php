<!DOCTYPE html>
<?php
	session_start();
	if(!empty($_GET['lng'])):
		$idioma = $_GET['lng'];
	elseif(empty($_GET['lng']) OR !isset($_GET['lng'])):
		$idioma = 'pt-BR';
    endif;
    
    include "./_con.php";
    include "./configDoacoes.php";
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
                                                    <h1 class="mb-0">Construindo por todos</h1>
                                                    <p class="text-large color-charcoal mb-0 mb-mobile-20">
                                                    Acreditamos que ser igreja vai além dos nossos encontros no templo. Ser igreja é ser transparente com os recursos financeiros, ser exemplo em nossas ações e <strong>transformar culturalmente o local onde estamos inseridos</strong>. Sabemos que não iremos construir isso sozinhos. Acredita que a igreja pode e deve fazer mais? Conheça nossas ações abaixo e se quiser passe a <strong>construir por todos</strong> você também.</p>
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
                        <div class="box rounded rounded shadow border-blue bkg-white">
                            <div class="column width-3">
                                <h3 class="mb-50 weight-bold">
                                    Vamos construir!
                                </h3>
                            </div>
                            <div class="column width-9">
                                <p class="color-charcoal text-xlarge" align="justify">Não estamos construindo uma mudança de curto prazo, começamos a estabelecer em cada novo membro uma cultura que nos leva além dos nossos encontros. Cada projeto acima é baseado na fé <strong>mas financiado pelo recurso de cada pessoa que quer promover a mudança em uma vida</strong>. É simples se uma criança receber uma nova oportunidade, ela passará a ter o poder (quando maior) de gerar oportunidade para um irmão, primo ou parente próximo, além de cuidar melhor dos seus pais. <strong>Nisso nós mudamos uma geração.</strong> Quer mudar o futuro comece plantando conosco, além de enviar sua contribuição mensal aqui você pode enviar uma contribuição avulsa agora, <a href="doacoes" target="_blank">clique aqui &rarr;</a></p>
                            </div>
                        </div>
                        <div class="box rounded rounded shadow border-blue bkg-white">
                            <div class="column width-4">
                                <div class="pricing-table rounded medium style-3 columns-1 mb-0">
                                    <div class="pricing-table-column mb-mobile-30 bkg-white">
                                        <div class="pricing-table-header">
                                            <h2 class="weight-bold">Constante</h2>
                                        </div>
                                        <div class="pricing-table-price">
                                            <h4>
                                                <span class="currency">R$</span> 9,90
                                                <span class="interval mt-10">por mês</span>
                                            </h4>
                                        </div>
                                        <div class="pricing-table-footer">
                                            <a class="button bkg-grey bkg-hover-grey color-white color-hover-white rounded hard-shadow" href="https://www.mercadopago.com.br/subscriptions/checkout?preapproval_plan_id=2c938084813ff7c60181582728ee0aa7" name="MP-payButton">QUERO SER PARCEIRO.</a>
                                            <label class="text-medium color-charcoal pt-10"><a href="https://www.mercadopago.com.br/subscriptions/checkout?preapproval_plan_id=2c938084813ff7c60181582728ee0aa7">Clique aqui</a>, caso o botão não funcione.</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="column width-4">
                                <div class="pricing-table rounded medium style-3 columns-1 mb-0">
                                    <div class="pricing-table-column mb-mobile-30 bkg-charcoal">
                                        <div class="pricing-table-header">
                                            <h2 class="weight-bold color-white">Parceiro fiel - TITO</h2>
                                        </div>
                                        <div class="pricing-table-price color-white">
                                            <h4>
                                                <span class="currency">R$</span> 210,86
                                                <span class="interval mt-10">por mês</span>
                                            </h4>
                                        </div>
                                        <div class="pricing-table-footer pt-10">
                                            <a href="https://www.mercadopago.com.br/subscriptions/checkout?preapproval_plan_id=2c938084813ff7c60181588b43620af8"  class=''>
                                                <button class='bkg-white bkg-hover-white color-charcoal color-hover-charcoal rounded hard-shadow '>QUERO SER PARCEIRO</button>
                                            </a>
                                            <label class="text-medium color-white pt-10"><a href="https://www.mercadopago.com.br/subscriptions/checkout?preapproval_plan_id=2c938084813ff7c60181588b43620af8">Clique aqui</a>, caso o botão não funcione.</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="column width-4">
                                <div class="pricing-table rounded medium style-3 columns-1 mb-0">
                                    <div class="pricing-table-column mb-mobile-30 bkg-blue">
                                        <div class="pricing-table-header">
                                            <h2 class="weight-bold color-white">Parceiro Fiel - Coríntios</h2>
                                        </div>
                                        <div class="pricing-table-price color-white">
                                                Escolha um valor acima de
                                            <h4>
                                                <span class="currency">R$</span> 9,90
                                                <span class="interval mt-10">por mês</span>
                                            </h4>
                                        </div>
                                        <div class="pricing-table-footer">
                                        <a href="https://www.mercadopago.com.br/subscriptions/checkout?preapproval_plan_id=2c9380848150bbff0181589de6320249" >
                                            <button class='bkg-facebook bkg-hover-facebook color-white color-hover-white rounded hard-shadow'>QUERO SER PARCEIRO</button>
                                        </a>
                                            <label class="text-medium color-white pt-10"><a href="https://www.mercadopago.com.br/subscriptions/checkout?preapproval_plan_id=2c9380848150bbff0181589de6320249">Clique aqui</a>, caso o botão não funcione.</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="box rounded rounded shadow border-blue bkg-white">
                            <div class="column width-12 v-align-middle">
                                <div>
                                    <h5 class="mb-10 color-white opacity-07">Educação</h5> 
                                    <h3 class="color-white">Instituto Turma de Líderes</h3>

                                    <p class="color-white text-xlarge" align="justify">
                                        Nenhuma transformação pessoal é estabelecida sem educação e elevação do padrão culural. <strong>Não acreditamos em assistencialismo</strong>, por este motivo iniciamos com este projeto. Queremos fundar uma instituição de ensino de excelência que ensine: Gestão, liderança e Teologia desde o ensino básico. Por que estes temas? Capacitar uma geração que consiga gerir melhor, que tenha capacidade de estar na frente e empreender e que conheça como Deus se manifesta.
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="box rounded rounded shadow border-blue bkg-white">
                            <div class="column width-12 v-align-middle">
                                <div>
                                    <h5 class="mb-10 color-white opacity-07">Apoio as Mulhures</h5> 
                                    <h3 class="color-white">Projeto Tamar</h3>

                                    <p class="color-white text-xlarge" align="justify">
                                        Diversas mulheres são violentadas diariamente, queremos dar voz e uma plataforma para elas não serem mais violentadas. Nesse projeto queremos ensinar <strong>artes de defesa pessoal, capacitação profissional, apoio psicológico e jurídico</strong>. Não há mudança cultural em uma sociedade que não protege suas mulheres.
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="box rounded rounded shadow border-blue bkg-white">
                            <div class="column width-12 v-align-middle">
                                <div>
                                    <h5 class="mb-10 color-white opacity-07">Crianças</h5> 
                                    <h3 class="color-white">Projeto Moisés</h3>

                                    <p class="color-white text-xlarge" align="justify">
                                        Gerar oportunidades através do esporte e outras ações para que haja transformação cultural na infância. Com um pensamento de ensino integral queremos oportunizar crescimento intelectual através do ITL, cultural com atividades complementares através do esporte e familiar com programas que insiram os responsáveis no processo. Uma criança "desamparada" pela sociedade se for abraçada e receber instrução pode ser um grande líder no futuro.
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="box rounded rounded shadow border-blue bkg-white">
                            <div class="column width-12 v-align-middle">
                                <div>
                                    <h5 class="mb-10 color-white opacity-07">Homens</h5> 
                                    <h3 class="color-white">Projeto BOAZ</h3>

                                    <p class="color-white text-xlarge" align="justify">
                                        Homens de principio que protegem, sustentam aqueles que estão em momentos dificeis. Que criam um futuro diferente para quem já não tinha esperança. Queremos tratar do caráter, da postura como homen, do distúrbio na pornografia e da infidelidades. 
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="box rounded rounded shadow border-blue bkg-white">
                            <div class="column width-12 center">
                                <h3 class="mb-50">Estabelecendo a Emanuel</h3>
                            </div>
                            <div class="row full-width">
                                <div class="column width-12 slider-column no-padding">
                                    <div class="tm-slider-container recent-slider" data-nav-dark data-carousel-visible-slides="2" data-nav-arrows="false" data-nav-keyboard="true" data-nav-pagination="true" data-nav-show-on-hover="false" data-carousel-1140="4" data-carousel-1024="3">
                                        <ul class="tms-slides">	
                                            <li class="tms-slide center">
                                                <div class="thumbnail rounded">
                                                    <img data-src="img/construindoportodos/Frente.png" src="images/blank.png" width="600" alt=""/>
                                                </div>
                                            </li>
                                            <li class="tms-slide center">
                                                <div class="thumbnail rounded">
                                                    <img data-src="img/construindoportodos/Frente - Iso.png" src="images/blank.png" width="600" alt=""/>
                                                </div>
                                            </li>
                                            <li class="tms-slide center">
                                                <div class="thumbnail rounded">
                                                    <img data-src="img/construindoportodos/templo midia.png" src="images/blank.png" width="600" alt=""/>
                                                </div>
                                            </li>
                                            <li class="tms-slide center">
                                                <div class="thumbnail rounded">
                                                    <img data-src="img/construindoportodos/Visao superior.png" src="images/blank.png" width="600" alt=""/>
                                                </div>
                                            </li>
                                            <li class="tms-slide center">
                                                <div class="thumbnail rounded">
                                                    <img data-src="img/construindoportodos/Entrada - lateral direita.png" src="images/blank.png" width="600" alt=""/>
                                                </div>
                                            </li>
                                            <li class="tms-slide center">
                                                <div class="thumbnail rounded">
                                                    <img data-src="img/construindoportodos/Entrada - lateral esquerda copa.png" src="images/blank.png" width="600" alt=""/>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="box rounded rounded shadow border-blue bkg-white">
                            <div class="column width-12">
                                <h3 class="mb-10 weight-bold">Estrutura da Emanuel</h3>
                            </div>
                            <div class="column width-12">
                                <p class="color-charcoal" align="justify">
                                    Iniciamos em <strong>Janeiro de 2021</strong> e <strong>estamos na fase de fundação</strong>, começamos do zero (<a href="quemsomos" target="_blank">clique aqui para conhecer <strong>nossa história</strong></a>). Precisamos de alguns equipamentos para termos uma estrutura básica e assim podermos focar nos projetos acima. Nossa visão é grande mas ainda somos pequenos, quer nos abençoar enviando algum destes itens? Colocamos abaixo os itens e os melhores preços que encontramos na publicação desta página, você pode doar comprando e enviando direto para o endereço: <strong>Rua Silva Jardim, 555. Vila São Luiz (Bairro), Duque de Caxias (Cidade), Rio de Janeiro (Estado), 25065-142 (CEP). Rafael Vieira de Oliveira Silva (Pastor)</strong>, ou se preferir pode levar direto para nossa igreja em um dos nossos cultos (Quarta-feira às 20h ou Domingo às 19:30h).
                                </p>
                                <p class="color-charcoal" align="justify">
                                    Só colocamos aquilo que é essencial e está fora da nossa realidade financeira neste momento. Categorizamos os itens em prioridade: <strong>vermelho</strong>, <strong>laranja</strong> e <strong>azul</strong>.
                                </p> 
                            </div>
                            <div class="column width-12">
                                <div class="row content-grid-4 flex">
                                    <div class="grid-item">
                                        <span class="client-name color-charcoal weight-bold text-medium pb-0">Ventiladores industriais</span> <br>
                                        <span class="client-description font-alt-1 pt-0 color-charcoal">Quantidade necessária: 4</span> <br>
                                        <span class="client-description font-alt-1 pt-0 color-charcoal">Valor: R$ 153,00 (und.)</span><br>
                                        <a href="https://www.pontofrio.com.br/ventilador-de-parede-50cm-new-premium-preto-ventisol-3856037/p/3856036?utm_medium=cpc&utm_source=GP_PLA&IdSku=3856036&idLojista=21754&utm_campaign=3P_Ar-e-Ventila%C3%A7%C3%A3o_SSC&gclid=Cj0KCQjw_8mHBhClARIsABfFgphovgc8ts-s1_STrwzOnE9jvuLk8ihB4epEbmJqhFHU3cOPiWHt0W4aAlMtEALw_wcB" target="_blank" class="label rounded bkg-red color-white color-hover-white weight-bold"> Doar</a>
                                    </div>
                                    <div class="grid-item">
                                        <span class="client-name color-charcoal weight-bold text-medium pb-0">Mesa de som 16 canais</span> <br>
                                        <span class="client-description font-alt-1 pt-0 color-charcoal">Quantidade necessária: 1</span> <br>
                                        <span class="client-description font-alt-1 pt-0 color-charcoal">Valor: 12x R$ 303,88</span><br>
                                        <a href="https://produto.mercadolivre.com.br/MLB-1353119750-mesa-de-som-digital-soundcraft-ui-16-mesa-16-canais-usb-wifi-_JM?matt_tool=18956390&utm_source=google_shopping&utm_medium=organic" target="_blank" class="label rounded bkg-red color-white color-hover-white weight-bold"> Doar</a>
                                    </div>
                                    <div class="grid-item">
                                        <span class="client-name color-charcoal weight-bold text-medium pb-0">Caixa Line (ativa)</span> <br>
                                        <span class="client-description font-alt-1 pt-0 color-charcoal">Quantidade necessária: 4</span> <br>
                                        <span class="client-description font-alt-1 pt-0 color-charcoal">Valor: 12x de R$ 206,95 (unid.)</span><br>
                                        <a href="https://www.pontofrio.com.br/caixa-line-array-mark-audio-vmk6-ativo-500w-rms-preto-1502621167/p/1502621167?utm_medium=cpc&utm_source=GP_PLA&IdSku=1502621167&idLojista=35165&utm_campaign=3P_Grupo-Baixo_SSC&gclid=Cj0KCQjw_8mHBhClARIsABfFgphbJQrOpWn8JFiZoiVMXCntBCBHLDmF1NqUGBqXMUqHP10E1os6dWkaAuk8EALw_wcB" target="_blank" class="label rounded bkg-red color-white color-hover-white weight-bold"> Doar</a>
                                    </div>
                                    <div class="grid-item">
                                        <span class="client-name color-charcoal weight-bold text-medium pb-0">Caixa de retorno (ativa)</span> <br>
                                        <span class="client-description font-alt-1 pt-0 color-charcoal">Quantidade necessária: 2</span> <br>
                                        <span class="client-description font-alt-1 pt-0 color-charcoal">Valor: 12x de R$ 147,58 (unid.)</span><br>
                                        <a href="https://www.pontofrio.com.br/monitor-autoamplificado-attack-vrm-1530-14032039/p/14032039?utm_medium=cpc&utm_source=GP_PLA&IdSku=14032039&idLojista=14076&utm_campaign=3P_Grupo-Baixo_SSC&gclid=Cj0KCQjw_8mHBhClARIsABfFgpjd2hLUsSiFIsni2kwlZVSZW_5nbKqvVXdoBZ2UjiXQMk6fU-oGVc4aAu5ZEALw_wcB" target="_blank" class="label rounded bkg-blue color-white color-hover-white weight-bold"> Doar</a>
                                    </div>
                                    <div class="grid-item">
                                        <span class="client-name color-charcoal weight-bold text-medium pb-0">Caixa de retorno (Passiva)</span> <br>
                                        <span class="client-description font-alt-1 pt-0 color-charcoal">Quantidade necessária: 2</span> <br>
                                        <span class="client-description font-alt-1 pt-0 color-charcoal">Valor: 12x de R$ 137,00 (unid.)</span><br>
                                        <a href="https://www.pontofrio.com.br/caixa-retorno-attack-vrm-1220-monitor-passivo-200w-11041611/p/11041611?utm_medium=cpc&utm_source=GP_PLA&IdSku=11041611&idLojista=36127&utm_campaign=3P_Grupo-Baixo_SSC&gclid=Cj0KCQjw_8mHBhClARIsABfFgphAnHV6hdmvTTAVY7Y8KFNVkyIafiEjid40J0iGetY2vEYYGScGp1UaAt1uEALw_wcB" target="_blank" class="label rounded bkg-blue color-white color-hover-white weight-bold"> Doar</a>
                                    </div>
                                    <div class="grid-item">
                                        <span class="client-name color-charcoal weight-bold text-medium pb-0">Caixa Subwoofer (Ativa)</span> <br>
                                        <span class="client-description font-alt-1 pt-0 color-charcoal">Quantidade necessária: 1</span> <br>
                                        <span class="client-description font-alt-1 pt-0 color-charcoal">Valor:  12x de R$ 447,51</span><br>
                                        <a href="https://www.pontofrio.com.br/subwoofer-amplificado-vrs1510a-attack-14887537/p/14887537?utm_medium=cpc&utm_source=GP_PLA&IdSku=14887537&idLojista=10523&utm_campaign=3P_Grupo-Baixo_SSC&gclid=Cj0KCQjw_8mHBhClARIsABfFgphZiCSOkzUnU5_991NdfNytmF2Ri8f763-1-b8RSgbP5TflJCiI5g4aAsAaEALw_wcB" target="_blank" class="label rounded bkg-blue color-white color-hover-white weight-bold"> Doar</a>
                                    </div>
                                    <div class="grid-item">
                                        <span class="client-name color-charcoal weight-bold text-medium pb-0">Caixa Subwoofer (Passiva)</span> <br>
                                        <span class="client-description font-alt-1 pt-0 color-charcoal">Quantidade necessária: 1</span> <br>
                                        <span class="client-description font-alt-1 pt-0 color-charcoal">Valor:  12x R$ 174,31</span><br>
                                        <a href="https://produto.mercadolivre.com.br/MLB-919078274-caixa-passiva-subwoofer-attack-vrs1560-600w-rms-_JM?matt_tool=18956390&utm_source=google_shopping&utm_medium=organic" target="_blank" class="label rounded bkg-blue color-white color-hover-white weight-bold"> Doar</a>
                                    </div>
                                    <div class="grid-item">
                                        <span class="client-name color-charcoal weight-bold text-medium pb-0">Teclado sensitivo</span> <br>
                                        <span class="client-description font-alt-1 pt-0 color-charcoal">Quantidade necessária: 1</span> <br>
                                        <span class="client-description font-alt-1 pt-0 color-charcoal">Valor:  12x R$ 166,18</span><br>
                                        <a href="https://www.americanas.com.br/produto/3236240609?loja=7282516001278&epar=bp_pl_00_go_im_todas_geral_gmv&opn=YSMESP&WT.srch=1&acc=e789ea56094489dffd798f86ff51c7a9&i=5e4612f749f937f625ab7478&o=608bfd63b650d410d8b2a45a&gclid=Cj0KCQjw_8mHBhClARIsABfFgpiNzOoZPiGj3227IPsBDvMB8ggfZVe2qwa1OcOAPTS1OqS0t1cnwsQaArdhEALw_wcB" target="_blank" class="label rounded bkg-blue color-white color-hover-white weight-bold"> Doar</a>
                                    </div>
                                    <div class="grid-item">
                                        <span class="client-name color-charcoal weight-bold text-medium pb-0">Cadeira plástica empilhável</span> <br>
                                        <span class="client-description font-alt-1 pt-0 color-charcoal">Quantidade necessária: 60</span> <br>
                                        <span class="client-description font-alt-1 pt-0 color-charcoal">Valor:  12x R$ 133,00 (Unid.)</span><br>
                                        <a href="https://www.google.com/search?q=Cadeira+Empilh%C3%A1vel+iso&sxsrf=ALiCzsa4WColk136PQOSujFV3B9OYlB6TQ:1655052382014&source=lnms&tbm=shop&sa=X&ved=2ahUKEwiPysHKrqj4AhWclZUCHTPbBCoQ_AUoAXoECAIQAw" target="_blank" class="label rounded bkg-orange color-white color-hover-white weight-bold"> Doar</a>
                                    </div>
                                    <div class="grid-item">
                                        <span class="client-name color-charcoal weight-bold text-medium pb-0">Projetor</span> <br>
                                        <span class="client-description font-alt-1 pt-0 color-charcoal">Quantidade necessária: 1</span> <br>
                                        <span class="client-description font-alt-1 pt-0 color-charcoal">Valor:  12x de R$ 266,58 (Unid.)</span><br>
                                        <a href="https://www.pontofrio.com.br/projetor-powerlite-s41-epson-13524538/p/13524538?utm_medium=cpc&utm_source=GP_PLA&IdSku=13524538&idLojista=37240&utm_campaign=apostas-conv-3p_smart-shopping&gclid=Cj0KCQjw_8mHBhClARIsABfFgpiltf22LNXs4LucVhHxVymzTTeWv9zoQIHz5Z58VrwpWFIQ5MalhCgaAmAiEALw_wcB" target="_blank" class="label rounded bkg-orange color-white color-hover-white weight-bold"> Doar</a>
                                    </div>
                                    <div class="grid-item">
                                        <span class="client-name color-charcoal weight-bold text-medium pb-0">Bebedouro industrial</span> <br>
                                        <span class="client-description font-alt-1 pt-0 color-charcoal">Quantidade necessária: 1</span> <br>
                                        <span class="client-description font-alt-1 pt-0 color-charcoal">Valor:  12X DE R$ 197,55 (Unid.)</span><br>
                                        <a href="https://www.lojadobebedouro.com.br/bebedouro-industrial-100-litros-knox-bebedouros?parceiro=3298&gclid=Cj0KCQjw_8mHBhClARIsABfFgpjYDhX7ifgDOKXupl8qyHpjvisM-VIY-U-OUWN--p9AtFz7FRGB8tkaAkIBEALw_wcB" target="_blank" class="label rounded bkg-red color-white color-hover-white weight-bold"> Doar</a>
                                    </div>
                                    <div class="grid-item">
                                        <span class="client-name color-charcoal weight-bold text-medium pb-0">Tapete p/ Sala das Crianças, kit c/ 12</span> <br>
                                        <span class="client-description font-alt-1 pt-0 color-charcoal">Quantidade necessária: 9</span> <br>
                                        <span class="client-description font-alt-1 pt-0 color-charcoal">Valor: R$ 124,99</span><br>
                                        <a href="https://www.amazon.com.br/Tatames-Tapetes-Borda-50cm-Preto/dp/B08JJJRG1R/ref=asc_df_B08JJJRG1R/?tag=googleshopp00-20&linkCode=df0&hvadid=424607859075&hvpos=&hvnetw=g&hvrand=5058382022438576442&hvpone=&hvptwo=&hvqmt=&hvdev=c&hvdvcmdl=&hvlocint=&hvlocphy=1031613&hvtargid=pla-983230805742&psc=1" target="_blank" class="label rounded bkg-red color-white color-hover-white weight-bold"> Doar</a>
                                    </div>
                                    <div class="grid-item">
                                        <span class="client-name color-charcoal weight-bold text-medium pb-0">Tv p/ Sala das Crianças</span> <br>
                                        <span class="client-description font-alt-1 pt-0 color-charcoal">Quantidade necessária: 2</span> <br>
                                        <span class="client-description font-alt-1 pt-0 color-charcoal">Valor: 12x de R$ 124,92 (Unid.)</span><br>
                                        <a href="https://www.pontofrio.com.br/smart-tv-led-32-hd-samsung-t4300-com-hdr-sistema-operacional-tizen-wi-fi-espelhamento-de-tela-dolby-digital-plus-hdmi-e-usb-2020-55006486/p/55006486?utm_medium=cpc&utm_source=GP_PLA&IdSku=55006486&idLojista=16&utm_campaign=1p_cluster4_smart-shopping&gclid=Cj0KCQjw_8mHBhClARIsABfFgpj_0C7A36kjLCjqlXKzPd8C5-K8tmOmmDUdj-Q-0CPdu-WkQT7R8xEaAt0GEALw_wcB" target="_blank" class="label rounded bkg-blue color-white color-hover-white weight-bold"> Doar</a>
                                    </div>
                                </div>
                            </div>
                        </div>
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