<?php
namespace Mode19\Mello;
use Flow\JSONPath\JSONPath;
use Mode19\Mello\DataModel\TrelloCard;
use Mode19\Mello\DataModel\TrelloList;
use Mode19\Mello\Utils;


class Client {
    private $client;
    private $debug = true;

    const TOKEN_KEY='TRELLO_API_TOKEN';
    const KEY_KEY='TRELLO_API_KEY';

    private string $KEY = '';
    private string $TOKEN = '';

    public function __construct()
    {
        $this->client = new \GuzzleHttp\Client();
        if(!getenv(Client::KEY_KEY) || !getenv(Client::TOKEN_KEY)) {
            printf("Please specify %s and %s environment variables\n", Client::KEY_KEY, Client::TOKEN_KEY);
            exit;
        }
        $this->KEY = getenv(Client::KEY_KEY);
        $this->TOKEN = getenv(Client::TOKEN_KEY);
    }

    public function getCardsOnList($listId) : array {
        return $this->makeRequest('GET',"https://api.trello.com/1/lists/$listId/cards/all", []);
    }

    public function getMembersBoards(): array {
        //curl 'https://api.trello.com/1/members/me/boards?key={yourKey}&token={yourToken}'
        $res = $this->makeRequest('GET',"https://api.trello.com/1/members/me/boards", []);
        //print_r((new JSONPath($res))->find("$*.name")->getData());
        return $res;
    }

    public function getListsForBoard($boardId) :array {
        //GET /1/boards/{id}/lists
        $res = $this->makeRequest('GET',"https://api.trello.com/1/boards/$boardId/lists", []);
        return $res;
    }

    public function updateCard(TrelloCard $card) {
       $res = $this->makeRequest('PUT', "https://api.trello.com/1/card/{$card->id}",[
           'name' => $card->name,
           'idList' => $card->idList,
           'idBoard' => $card->idBoard,
           'closed' => $card->isClosed,
           'desc' => $card->desc,
       ]);
    }

    public function createCard(string $cardTitle, string $idList)
    {
        $res = $this->makeRequest('POST', "https://api.trello.com/1/cards",[
            'name' => $cardTitle,
            'idList' => $idList
        ]);
    }

    public function updateList(TrelloList $list) {
        $res = $this->makeRequest('PUT', "https://api.trello.com/1/list/{$list->id}",[
            'name' => $list->name,
        ]);
    }

    private function makeRequest($method, $url, $params) {
        if( $this->debug === true ) {
            Utils::log("________________________________________________________________________________\n");
            Utils::log(sprintf("ACTION: %s: %s \n", $method, $url));
            Utils::log("--------------------------------------------------------------------------------\n");
        }

        /*
            $authString = $USER . ':' . $APIKEY;
            //Utils::log("auth=%s\n", $authString);
            $base64String = base64_encode($authString);
            $params2 = array_merge($params, [ 'headers' =>  [
                'content-type' => 'application/json',
                //'Authorization' => "Basic $base64String"
            ]]);

            $res = $client->request($method, $url, $params2);
        */
        //?key=$KEY&token=$TOKEN
        $params['key'] = $this->KEY;
        $params['token'] = $this->TOKEN;

        $combinedParams = [ 'query' => $params];
        if( $this->debug == true ) {
            Utils::log(sprintf("Parameters: %s\n", json_encode($combinedParams)));
        }

        $res = $this->client->request($method, $url,$combinedParams);
       
        $resObject = json_decode($res->getBody());
        if( $this->debug === true ) {
            Utils::log(sprintf("HTTP %s returned. body=%s\n", $res->getStatusCode(), json_encode($resObject, JSON_PRETTY_PRINT)));
            Utils::log("--------------------------------------------------------------------------------\n");
        }
        return $resObject;
    }



}

?>
