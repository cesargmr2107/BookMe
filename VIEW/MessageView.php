<?php

include_once './VIEW/BaseView.php';

class MessageView extends BaseView {

	protected function body(){

		// DEBUG: Check result
		// echo '<pre>' . var_export($this->data["result"], true) . '</pre>';
		
		// Get msg code
		$code = $this->data["result"]["code"];

		$this->includeTitle("i18n-systemMsgs", "h1");

		echo "<h3>$code : <span class='i18n-$code'></span></h3>";

		if(array_key_exists("atributeErrors", $this->data["result"])){
			echo "<p class='i18n-atributeErrors'></p>";
			echo "<ul>";
			foreach ($this->data["result"]["atributeErrors"] as $atribute => $errors) {
				echo "<li>" . $atribute . ":</li>";
				echo "<ul>";
				foreach ($errors as $check => $code) {
					echo "<li>$code: <span class='i18n-$code'></span></li>";
				}
				echo "</ul>";
			}
			echo "</ul>";
		}
		
		if (array_key_exists("link", $this->data)){
			?>
				<a href="<?= $this->data["link"]; ?>">
					<span class="<?=$this->icons["BACK"]?>"></span>
				</a>
			<?php
		} else {
			$this->includeButton("BACK", "goBackForm", "post", $this->data["controller"], $this->data["action"]);
		}
	}

	

}
?>
