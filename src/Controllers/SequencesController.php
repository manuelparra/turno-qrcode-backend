<?php namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Controllers\BaseController;
use Endroid\QrCode\QrCode;

class SequencesController extends BaseController {

    public function getAll($request, $response, $args) {

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
        $pdo = $this->container->get('db');

        $sql = "SELECT MAX(sequences.sequence) AS sequence
                FROM sequences";

        $query = $pdo->query($sql);

        $result = $query->fetch();
        $sequence = $result->sequence;

        $url = 'https://numeros.papiro.es/turno/sec=' . $sequence;

        // Creare una nueva instancia de la clase
        $qrCode = new QrCode($url); // Le paso como parametro el texto recibido via ajax
        $qrCode->setSize('400 px'); // Alteramos el tamaño por defecto
        $image = $qrCode->writeString(); // Salida en formato de texto
        $imageData = base64_encode($image); // Codifico la imagen usando base64_encode
        $payload = (object) ["src" => "data:image/png;base64," . $imageData]; // Salida del código QR
        $response->getBody()->write(json_encode($payload));

        return $response->withHeader('Content-Type', 'application/json')
                        ->withStatus(200);
    }
}
