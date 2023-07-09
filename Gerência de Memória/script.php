<?php
session_start();
class Bloco
{
    private $espacoTotal;
    private $espacoEmUso;
    public function __construct($espacoTotal)
    {
        $this->espacoTotal = $espacoTotal;
        $this->espacoEmUso = 0;
    }

    public function getEspacoTotal()
    {
        return $this->espacoTotal;
    }

    public function getEspacoEmUso()
    {
        return $this->espacoEmUso;
    }

    public function alocar($tamanho)
    {
        $this->espacoEmUso += $tamanho;
    }

    public function desalocar($tamanho)
    {
        $this->espacoEmUso -= $tamanho;
        if ($this->espacoEmUso < 0) {
            $this->espacoEmUso = 0;
        }
    }

    public function isAlocado()
    {
        return $this->espacoEmUso > 0;
    }
}

class Memoria
{
    private $blocos;
    private $ultimoBlocoModificado;

    public function __construct()
    {
        $this->blocos = array();
        $this->ultimoBlocoModificado = null;
    }

    public function addBloco($bloco)
    {
        $this->blocos[] = $bloco;
    }

    public function getBlocos()
    {
        return $this->blocos;
    }

    public function setUltimoBlocoModificado($bloco)
    {
        $this->ultimoBlocoModificado = $bloco;
    }

    public function getUltimoBlocoModificado()
    {
        return $this->ultimoBlocoModificado;
    }

    public function reiniciar()
    {
        foreach ($this->blocos as $bloco) {
            $bloco->desalocar($bloco->getEspacoEmUso());
        }
    }
}

class Gerenciador
{
    public static function firstFit($tamanho, $memoria)
    {
        $blocos = $memoria->getBlocos();

        foreach ($blocos as $bloco) {
            if (!$bloco->isAlocado() && $bloco->getEspacoTotal() >= $tamanho) {
                $bloco->alocar($tamanho);
                $memoria->setUltimoBlocoModificado($bloco);
                return;
            }
        }

        echo "<br><span style='color: red;font-weight: bold;font-size:20px'>Não há espaço disponível para alocar o processo.</span><br>";
    }

    public static function bestFit($tamanho, $memoria)
    {
        $blocosDisponiveis = array();

        foreach ($memoria->getBlocos() as $bloco) {
            if (!$bloco->isAlocado() && $bloco->getEspacoTotal() >= $tamanho) {
                $blocosDisponiveis[] = $bloco;
            }
        }

        if (count($blocosDisponiveis) > 0) {
            usort($blocosDisponiveis, function ($a, $b) {
                return $a->getEspacoTotal() - $b->getEspacoTotal();
            });

            $blocoSelecionado = $blocosDisponiveis[0];
            $blocoSelecionado->alocar($tamanho);
            $memoria->setUltimoBlocoModificado($blocoSelecionado);
        } else {
            echo "<br><span style='color: red;font-weight: bold;font-size:20px'>Não há espaço disponível para alocar o processo.</span><br>";
        }
    }

    public static function worstFit($tamanho, $memoria)
    {
        $blocosDisponiveis = array();

        foreach ($memoria->getBlocos() as $bloco) {
            if (!$bloco->isAlocado() && $bloco->getEspacoTotal() >= $tamanho) {
                $blocosDisponiveis[] = $bloco;
            }
        }

        if (count($blocosDisponiveis) > 0) {
            usort($blocosDisponiveis, function ($a, $b) {
                return $b->getEspacoTotal() - $a->getEspacoTotal();
            });

            $blocoSelecionado = $blocosDisponiveis[0];
            $blocoSelecionado->alocar($tamanho);
            $memoria->setUltimoBlocoModificado($blocoSelecionado);
        } else {
            echo "<br><span style='color: red;font-weight: bold;font-size:20px'>Não há espaço disponível para alocar o processo.</span><br>";
        }
    }
}

function mostraMemoria($memoria)
{
    echo ' <link href="style.css" rel="stylesheet"/>';
    $blocos = $memoria->getBlocos();
    $numBlocos = count($blocos);
    echo "<br>";
    echo "<table style='margin: 0 auto;'>";
    for ($i = 0; $i < $numBlocos; $i += 2) {
        echo "<tr>";
        echo "<td style='padding: 30px; text-align: center;'>";
        echo '<div class="bloconum"> Bloco ' . ($i + 1) . ':</div>';
        echo '<div class="blocoesp"> Tamanho Total: ' . $blocos[$i]->getEspacoTotal();
        echo  '</br>Tamanho em Uso: ' . $blocos[$i]->getEspacoEmUso() . '</div>';
        echo "</td>";

        if ($i + 1 < $numBlocos) {
            echo "<td style='padding: 30px; text-align: center;'>";
            echo '<div class="bloconum"> Bloco ' . ($i + 2) . ':</div>';
            echo '<div class="blocoesp">Tamanho Total: ' . $blocos[$i + 1]->getEspacoTotal();
            echo '</br>Tamanho em Uso: ' . $blocos[$i + 1]->getEspacoEmUso() . '</div>';
            echo "</td>";
        }

        echo "</tr>";
    }
    echo "</table>";
}

function verificaOpcao($opcao, $memoria)
{
    echo ' <link href="style.css" rel="stylesheet"/>';
    switch ($opcao) {
        case 1:
            echo '<div class="escolha">Algoritmo First Fit selecionado.</div>';
            $tamanho = intval($_POST['tamanho']);
            Gerenciador::firstFit($tamanho, $memoria);
            break;

        case 3:
            echo '<div class="escolha">Algoritmo Best Fit selecionado.</div>';
            $tamanho = intval($_POST['tamanho']);
            Gerenciador::bestFit($tamanho, $memoria);
            break;
        case 4:
            echo '<div class="escolha">Algoritmo Worst Fit selecionado.</div>';
            $tamanho = intval($_POST['tamanho']);
            Gerenciador::worstFit($tamanho, $memoria);
            break;
    }
}

function reiniciarMemoria($memoria)
{
    foreach ($memoria->getBlocos() as $bloco) {
        $bloco->alocar(0);
    }
    $memoria->setUltimoBlocoModificado(null);
}

$memoria = new Memoria();
$memoria->addBloco(new Bloco(10));
$memoria->addBloco(new Bloco(4));
$memoria->addBloco(new Bloco(20));
$memoria->addBloco(new Bloco(18));
?>

<!DOCTYPE html>
<html>

<head>
    <link href="https://fonts.googleapis.com/css?family=Poppins" rel="stylesheet" />
    <link href="style.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <title class="geren"> Gerenciamento de Espaço em Memória</title>
</head>

<body style="background-color: #a598ee81;">

    <div class="container py-3">
        <header>
            <div class="d-flex flex-column align-items-center pb-3 mb-4 border-bottom">
                <a href="index.html" class="d-flex align-items-center text-dark text-decoration-none">
                    <span class="fs-4">Sistemas Operacionais</span>
                </a>
            </div>
        </header>

        <h1 style="text-align: center;">Gerenciamento de Espaço em Memória</h1>
        <div class="row justify-content-center py-3">
            <div class="col-4">
                <div class="text-center">
                    <form method="POST" action="">
                        <label for="tamanho">Tamanho do processo</label>
                        <input type="number" id="tamanho" name="tamanho" class="form-control">
                </div>
            </div>
            <div class="col-4">
                <label for="opcao" class="opc">Escolha o algoritmo</label>
                <br>
                <select id="opcao" name="opcao" class="form-select" data-trigger="">
                    <option value="1">First Fit</option>
                    <option value="3">Best Fit</option>
                    <option value="4">Worst Fit</option>
                </select>
                <br>
            </div>
        </div>

        <div class="d-grid gap-2 d-md-block text-center">
            <form method="POST" style="display: inline-block;">
                <input class="btn btn-outline-primary" type="submit" value="Enviar">
            </form>
            <form method="POST" style="display: inline-block;">
                <input class="btn btn-outline-warning" type="hidden" name="reiniciar" value="1">
                <input class="btn btn-outline-dark" type="submit" value="Reiniciar Memória">
            </form>
        </div>

        <?php
        if (isset($_POST['opcao'])) {
            $opcao = intval($_POST['opcao']);
            verificaOpcao($opcao, $memoria);
        }

        if (isset($_POST['reiniciar'])) {
            reiniciarMemoria($memoria);
            echo "<div class='escolha'>Memória reiniciada.</div>";
        }
        ?>

        <?php mostraMemoria($memoria); ?>

</body>

</html>