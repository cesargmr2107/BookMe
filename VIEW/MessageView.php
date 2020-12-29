<?php

class MessageView{

	public static function withLink($response, $link){
		self::render( $response["code"],  $response["msg"], $link, "", "");
	}

	public static function withController($response, $controller, $action){
		self::render( $response["code"],  $response["msg"], "", $controller, $action);
	}

	private static function render($code, $msg, $link, $controller, $action){
		include './VIEW/components/header.php';
		?>
			<h1>Mensaje del sistema<h1>

			<h1>
				<?php echo $code ." - " . $msg; ?>
			</h1>

		<?php
			if ($link){
				?>
					<a href="<?php echo $link; ?>">
						<span class="fas fa-arrow-left"></span>
					</a>
				<?php
			} else {
		?>
				<!-- Hidden form -->
				<form name="goBackForm" action="index.php">
					<!-- Hidden fields -->
					<input type="hidden" name="controller" value="<?php echo $controller; ?>"/>
					<input type="hidden" name="action" value="<?php echo $action; ?>"/>
					<span class="fas fa-arrow-left" onclick="sendForm(document.goBackForm, true)"></span>
				</form>
		<?php
			}
			include './VIEW/components/footer.php';
		}

}
?>
