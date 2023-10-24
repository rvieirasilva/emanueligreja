<?php
    //Validação do CPF
    $cpf_d1 = ($cpf[0] * 10) + ($cpf[1] * 9) + ($cpf[2] * 8) + ($cpf[3] * 7) + ($cpf[4] * 6) + ($cpf[5] * 5) + ($cpf[6] * 4) + ($cpf[7] * 3) + ($cpf[8] * 2); //Soma os 9 valores para ter o 1º dígito.
    $cpf_d1 = $cpf_d1 / 11; //Após somar cada número dividi o resultado pelos 11 digitos.
    $cpf_d1 = number_format($cpf_d1, 2, '.', ''); //Arredonda a dízima periódica para dois digitos.
    $cpf_d1 = explode('.', $cpf_d1); // tira o quociente (20) de 20,90 e deixa a segunda parte (90);
    $cpf_d1 = $cpf_d1[1] / 10; //Divite a segunda parte por 10 para virar 9,0.
        if($cpf_d1 < 2):
            $cpf_d1 = 0;
        else:
            $cpf_d1 = 11 - $cpf_d1; //Subtraí 11 pelo valor acima (11 - 9,0)
            $cpf_d1 = explode('.', $cpf_d1); //tira a primeira parte 
            $cpf_d1 = $cpf_d1[0]; //Resulta no primeiro digíto -X.
        endif;
        //Descobre-se através da fórmula acima o primeiro digito do CPF.
    $cpf_d2 = ($cpf[0] * 11) + ($cpf[1] * 10) + ($cpf[2] * 9) + ($cpf[3] * 8) + ($cpf[4] * 7) + ($cpf[5] * 6) + ($cpf[6] * 5) + ($cpf[7] * 4) + ($cpf[8] * 3) + ($cpf_d1 * 2);	
    $cpf_d2 = $cpf_d2 / 11; //Após somar cada número dividi o resultado pelos 11 digitos.
    $cpf_d2 = number_format($cpf_d2, 2, '.', ''); //Arredonda a dízima periódica para dois digitos.
    $cpf_d2 = explode('.', $cpf_d2); // tira o quociente (20) de 20,90 e deixa a segunda parte (90);
    $cpf_d2 = $cpf_d2[1] / 10; //Divite a segunda parte por 10 para virar 9,0.
        if($cpf_d2 < 2):
            $cpf_d2 = 0;
        else:
            $cpf_d2 = 11 - $cpf_d2; //Subtraí 11 pelo valor acima (11 - 9,0)
            $cpf_d2 = explode('.', $cpf_d2); //tira a primeira parte 
            $cpf_d2 = $cpf_d2[0]; //Resulta no primeiro digíto -X.
        endif;
    $cpf_verificado = $cpf[0].$cpf[1].$cpf[2].$cpf[3].$cpf[4].$cpf[5].$cpf[6].$cpf[7].$cpf[8].$cpf_d1.$cpf_d2;
    $cpfinvalido = preg_match ( '/(\d)\1{10}/', $cpf );
    