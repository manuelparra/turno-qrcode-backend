<?php namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Controllers\BaseController;
use Endroid\QrCode\QrCode;
use PDOException;

class SequencesController extends BaseController {

    public function getSecuences($request, $response, $args) {

        $pdo = $this->container->get('db');

        $sql = "SELECT sequences.sequence, sequences.date_reg, sequences.time_reg,
                sequences.ip, sequences.turn, sequences.date_scan_qr,
                sequences.time_scan_qr, sequences.id_user_atent,
                sequences.date_atent, sequences.time_atent
                FROM sequences";

        $query = $pdo->query($sql);

        $payload = $query->fetchAll();

        $response->getBody()->write(json_encode($payload));

        return $response->withHeader('Content-Type', 'application/json')
                        ->withStatus(200);
    }

    public function getLastSequence($request, $response, $args) {
        try {
            $pdo = $this->container->get('db');

            $sql = "SELECT MAX(sequences.sequence) AS sequence
                    FROM sequences";

            $query = $pdo->query($sql);

            $result = $query->fetch();
            $sequence = $result->sequence;

            $url = 'https://numeros.papiro.es/turno/' . $sequence;

            $query = null;
            $pdo = null;

            // Creare una nueva instancia de la clase
            $qrCode = new QrCode($url); // Le paso como parametro el texto recibido via ajax
            $qrCode->setSize('400 px'); // Alteramos el tamaño por defecto
            $image = $qrCode->writeString(); // Salida en formato de texto
            $imageData = base64_encode($image); // Codifico la imagen usando base64_encode
            $payload = (object) ["src" => "data:image/png;base64," . $imageData]; // Salida del código QR
        } catch ( PDOException $e ) {
            $payload = '{error} : {"text": ' . $e . '}';
        }

        $response->getBody()->write(json_encode($payload));

        return $response->withHeader('Content-Type', 'application/json')
                        ->withStatus(200);
    }

    public function getSecuence($request, $response, $args) {
        $ipAddress = $request->getAttribute('ip_address');

        $sec = intval($request->getAttribute('sec'));

        $yourturn = "";

        //$payload = (object) ["sec" => $sec, "ip" => $ipAddress, "fecha" => date("Y-m-d")];

        try {
            $pdo = $this->container->get('db');

            $sql = "SELECT sequences.sequence, sequences.turn, sequences.ip
                    FROM sequences
                    WHERE sequences.sequence = " . $sec . ";";

            $query = $pdo->query($sql);

            $result = $query->fetch();

            if (!$result->sequence) {
                $yourturn = 'err sne'; // secuencia no existe
            } else {
                if (!$result->turn) {
                    $sql = "SELECT sequences.turn
                            FROM sequences
                            WHERE sequences.ip = '" . $ipAddress . "' AND sequences.date_scan_qr = DATE(NOW()) AND sequences.id_status = 2;";
                    $query = $pdo->query($sql);
                    $result = $query->fetch();
                    if ($result->turn) {
                        $yourturn = $result->turn;
                    } else {
                        $sql = "UPDATE sequences AS tableAlpha
                                INNER JOIN (SELECT " . $sec . " AS sequence, (IFNULL(MAX(sequences.turn), 0) + 1) AS maxturn
                                            FROM sequences
                                            WHERE sequences.date_scan_qr = DATE(NOW())) AS tableBeta
                                ON tableAlpha.sequence = tableBeta.sequence
                                SET tableAlpha.turn = tableBeta.maxturn,
                                    tableAlpha.date_scan_qr = DATE(NOW()),
                                    tableAlpha.time_scan_qr = TIME(NOW()),
                                    tableAlpha.ip = '" . $ipAddress . "',
                                    tableAlpha.id_status = 2
                                WHERE tableAlpha.sequence = " . $sec . ";";
                        $query = $pdo->query($sql);

                        $sql = "INSERT INTO sequences (sequences.date_reg, sequences.time_reg, sequences.id_status)
                                VALUES (DATE(NOW()), TIME(NOW()), 1);";
                        $query = $pdo->query($sql);

                        $sql = "SELECT sequences.turn
                                FROM sequences
                                WHERE sequences.sequence = " . $sec . ";";

                        $query = $pdo->query($sql);
                        $result = $query->fetch();
                        $yourturn = $result->turn;
                    }
                } else {
                    if ($result->ip == $ipAddress) $yourturn = $result->turn; else $yourturn = 'err soi'; // secuecia otra con otra ip
                }
            }

            $query = null;
            $pdo = null;

            $payload = (object) ["turn" => $yourturn]; // Salida del código QR
        } catch ( PDOException $e ) {
            $payload = '{error} : {"text": ' . $e . '}';
        }

        $response->getBody()->write(json_encode($payload));

        return $response->withHeader('Content-Type', 'application/json')
                        ->withStatus(200);
    }
}
