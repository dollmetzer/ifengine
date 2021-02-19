<?php
include __DIR__ . '/bootstrap.php';
$command = '';
if (!empty($_POST['command'])) {
    $command = $_POST['command'];
}
$game->process($command);
?>
<!DOCTYPE html>
<html lang="<?php echo $game->getLanguage(); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo $game->getRoom(); ?></title>
    <style>
        body {
            margin:0;
            padding:0;
            background:#ffffff;
            color:#000000;
        }
        div.output {
            padding: 0.5em;
        }
        div.input {
            padding: 0.5em;
        }
        table.headline {
            margin:0;
            width:100%;
            background: #dddddd;
            border-bottom:1px solid black;
        }
        ul.output {
            margin:0;
            padding:0;
            list-style: none;
        }
        li.command {
            margin:.5em;
            padding:.5em;
            background:#dddddd;
            color:#333333;
        }
        li.answer {
            margin:0.5em;
            padding:0.5em
            background:#ffffff;
            color:#000000;
        }
    </style>
</head>
<body>

<table class="headline">
    <tr>
        <td><strong><?php echo $game->getRoom(); ?></strong></td>
        <td style="text-align:right;">Score: <?php echo $game->getScore() . '/' . $game->getMaxScore() . ' | ' . $game->getMoves(); ?></td>
    </tr>
</table>

<div class="output">
    <ul class="output">
        <?php
            $output = $game->getOutput() ;
            foreach($output as $line) {
                if (substr($line, 0,1) == '>') {
                    echo '<li class="command">';
                } else {
                    echo '<li class="answer">';
                }
                echo nl2br($line);
                echo "</li>\n";
            }
        ?>
    </ul>
</div>

<div class="input">
    <form action="" name="adventure" method="post">
        <p id="inputline"><input id="command" type="text" name="command" value="" maxlength="255" placeholder="Was soll ich tun?"/>
            <input id="text" type="submit" value="senden" style="display:inline;"/>
            <input id="voice" type="button" value="Sprich..." onclick = "start();" style="display:none;"></input>
        </p>
    </form>

</div>

<script type="text/javascript">
    document.getElementById('command').focus();
</script>

</body>
<script>

    if ('speechSynthesis' in window) {
        var synthesis = window.speechSynthesis;
        var utterance = new SpeechSynthesisUtterance("Hello World");

        utterance.text = "<?php echo str_replace("\n", '', array_pop($output)); ?>";
        synthesis.speak(utterance);
    }

    if ('webkitSpeechRecognition' in window) {
        var recognition = new webkitSpeechRecognition();
        recognition.continuous = true;
        document.getElementById("voice").style.display = "inline";
        document.getElementById("text").style.display = "none";
        function start(){
            spoken = '';
            document.getElementById('command').style.backgroundColor = 'red';
            recognition.onresult = function(event) {
                for(var i=0; i<event.results.length; i++){
                    spoken += event.results[i][0].transcript
                    document.getElementById('command').value = spoken;
                    document.adventure.submit();
                }
            }
            recognition.start();
        }
    }
</script>
</html>
