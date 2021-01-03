<?php

include_once './VIEW/BaseView.php';

class MessageView extends BaseView {

	protected function body(){

		// DEBUG: Check result
		// echo '<pre>' . var_export($this->data["result"], true) . '</pre>';

		$this->includeTitle("Mensajes del sistema", "h1");

		$errorTitle = $this->data["result"]["code"] . " - " . $this->data["result"]["msg"];
		$this->includeTitle($errorTitle, "h3");

		if (array_key_exists("link", $this->data)){
			?>
				<a href="<?= $this->data["link"]; ?>">
					<span class="<?=$this->icons["BACK"]?>"></span>
				</a>
			<?php
		} else {
			$this->includeButton("BACK", "goBackForm", "post", $this->data["controller"], $this->data["action"]);
		}

		if(array_key_exists("atributeErrors", $this->data["result"])){
			echo "<p>Error(es) de atributo</p>";
			echo "<ul>";
			foreach ($this->data["result"]["atributeErrors"] as $atribute => $errors) {
				echo "<li>" . $atribute . ":</li>";
				echo "<ul>";
				foreach ($errors as $check => $info) {
					echo "<li>" . $info["code"] . " - " .  $info["msg"] . "</li>";
				}
				echo "</ul>";
			}
			echo "</ul>";
		}
		
	}

	

}
?>
