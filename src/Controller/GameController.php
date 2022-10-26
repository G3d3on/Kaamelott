<?php

namespace App\Controller;

use Ratchet\Wamp\Exception;
use Ratchet\ConnectionInterface;
use Ratchet\WebSocket\MessageComponentInterface;

use App\Repository\ComposeRepository;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class GameController extends AbstractController implements MessageComponentInterface
{
    
    /**
     * @Route("/terrain", name="terrain")
     * @IsGranted("ROLE_USER", message="No access! Get out!")
     */
    public function index(): Response
    {
        return $this->render('terrain/terrain.html.twig');
    }
    
    
    /**
    * @Route("/carte", name="carte", methods={"POST", "GET"})
    * @IsGranted("ROLE_USER", message="No access! Get out!")
    */
    public function Card()
    {
        $user = $this->getUser();
        $decks = $user->getDecks(); 

        $deck = $decks[0];

        $compositions = $deck->getComposes();

        return new JsonResponse($compositions);
    }

    
    public function onOpen(ConnectionInterface $conn) {

        // Store the new connection to send messages to later
        $this->clients->attach($conn);
        
        echo "New connection! ".$conn->resourceId."\n";
    }

    public function onMessage(ConnectionInterface $from, $msg) {

        $numRecv = count($this->clients);
        var_dump($numRecv);
        echo sprintf('Connection %d sending message "%s" to %d other connection%s' . "\n"
            , $from->resourceId, $msg, $numRecv, $numRecv == 1 ? '' : 's');

        foreach ($this->clients as $client) {
            if ($from !== $client) {
                // The sender is not the receiver, send to each client connected
                $client->send($msg);
            }
        }
    }

    public function onClose(ConnectionInterface $conn) {

        // The connection is closed, remove it, as we can no longer send it messages
        $this->clients->detach($conn);

        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        
        echo "An error has occurred: {$e->getMessage()}\n";

        $conn->close();
    }
}
