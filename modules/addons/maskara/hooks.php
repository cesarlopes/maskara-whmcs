<?php
//Laravel DataBase
use WHMCS\Database\Capsule;
//Bloqueia o acesso direto ao arquivo
if (!defined("WHMCS"))
	{
	 die("Acesso restrito!");
	}
	//Cria o Hook
	function maskara($vars) {
    		//Pegando URL do sistema no banco
    		foreach (Capsule::table('tblconfiguration')->WHERE('setting', 'SystemURL')->get() as $system){
	    		$urlsistema = $system->value;
			}
		//Pegando informações da tabela do módulo.
		/** @var stdClass $mask */
		foreach (Capsule::table('mod_maskara')->get() as $mask){
		    $cpfcampo = $mask->cpf;
		    $nascimentocampo = $mask->data_nascimento;
		    $cnpjcampo = $mask->cnpj;
			$celular = $mask->celular;
		}
		//Criando o Javascript
		$javascript  = '';
		//Chamando o Jquery da Mascara
		$javascript .= '<script type="text/javascript" src="'.$urlsistema.'/modules/addons/maskara/jquery.maskedinput.min.js"></script><script src="'.$urlsistema.'/modules/addons/maskara/cep.js"></script>';
		//Verifica se o campo é o mesmo do CPF X CNPJ
		if($cpfcampo==$cnpjcampo){
			//Chamando as mascaras
			$javascript .= '<script type="text/javascript">jQuery(function($){ ';			
			//Data de Nascimento
			$javascript .= '$("#customfield'.$nascimentocampo.'").mask("99/99/9999", {placeholder: "dd/mm/aaaa"}); ';
			//Celular
			$javascript .= 'var maskBehavior=function(a){return 11===a.replace(/\D/g,"").length?"(00) 00000-0000":"(00) 0000-00009"},options={onKeyPress:function(a,o,e,n){e.mask(maskBehavior.apply({},arguments),n)}};$(".phone").mask(maskBehavior,options);';
			$javascript .= '$("#customfield'.$celular.'").mask(maskBehavior, options);';
			//Telefone
			$javascript .= '$("#phonenumber").mask(maskBehavior, options);';
			$javascript .= '$("#inputPhone").mask(maskBehavior, options);';
			//CEP
			$javascript .= '$("#postcode").mask("99999-999"); ';
			$javascript .= '$("#inputPostcode").mask("99999-999"); ';
			//Fechando Jquery das mascaras
			$javascript .= ' });</script>';
			//CPF CNPj mesmo campo			
			$javascript .= 
			'
			<script type="text/javascript">
    		window.onload=function(){
				// Mascara de CPF e CNPJ
				var CpfCnpjMaskBehavior = function (val) {
							return val.replace(/\D/g, \'\').length <= 11 ? \'000.000.000-009\' : \'00.000.000/0000-00\';
						},
					cpfCnpjpOptions = {
						onKeyPress: function(val, e, field, options) {
						field.mask(CpfCnpjMaskBehavior.apply({}, arguments), options);
					  }
					};
				$(function() {
					$(\':input[id=customfield'.$cpfcampo.']\').mask(CpfCnpjMaskBehavior, cpfCnpjpOptions);
				})
			}
			</script>
			';
		}
		else{
			//Chamando as mascaras
			$javascript .= '<script type="text/javascript">jQuery(function($){ ';
			//CPF
			$javascript .= '$("#customfield'.$cpfcampo.'").mask("999.999.999-99"); ';
			//CNPJ
			$javascript .= '$("#customfield'.$cnpjcampo.'").mask("99.999.999/9999-99"); ';
			//Celular
			$javascript .= 'var maskBehavior=function(a){return 11===a.replace(/\D/g,"").length?"(00) 00000-0000":"(00) 0000-00009"},options={onKeyPress:function(a,o,e,n){e.mask(maskBehavior.apply({},arguments),n)}};$(".phone").mask(maskBehavior,options);';
			$javascript .= '$("#customfield'.$celular.'").mask(maskBehavior, options);';
			//Data de Nascimento
			$javascript .= '$("#customfield'.$nascimentocampo.'").mask("99/99/9999", {placeholder: "dd/mm/aaaa"}); ';
			//Telefone
			$javascript .= '$("#phonenumber").mask(maskBehavior, options);';
			$javascript .= '$("#inputPhone").mask(maskBehavior, options);';
			//CEP
			$javascript .= '$("#postcode").mask("99999-999"); ';
			$javascript .= '$("#inputPostcode").mask("99999-999"); ';
			//Fechando Jquery das mascaras
			$javascript .= ' });</script>';
		}
		
		//Retorna o Javascript
		return $javascript;
	}
	//Adicionando o hook as páginas
	add_hook("ClientAreaFooterOutput",1,"maskara");
?>