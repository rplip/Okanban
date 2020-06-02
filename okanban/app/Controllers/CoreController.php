<?php


class CoreController {
    private $router;
    public function __construct($routerParam)
    {
        $this->router = $routerParam;
    }
    
    protected function show($viewName, $viewData = [])
    {   
        
        $viewData['currentPage'] = $viewName;
        
        
        extract($viewData);

        $router = $this->router;
       
        require __DIR__ . '/../views/' . $viewName . '.tpl.php';
    }

        /**
    * Méthode permettant d'afficher/retourner un JSON à l'appel Ajax effectué
    *
    * @param mixed $data
    */
    protected function showJson($data)
    {
        // Autorise l'accès à la ressource depuis n'importe quel autre domaine
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Credentials: true');
        // Dit au navigateur que la réponse est au format JSON
        header('Content-Type: application/json');
        // La réponse en JSON est affichée
        echo json_encode($data);
    }
}
