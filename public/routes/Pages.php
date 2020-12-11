<?php # Pages controller example
namespace routes;

use Exception;

class Pages
{
    private \FastRoute\RouteCollector $r;
    private ?array $queryParameters;

    public function __construct(\FastRoute\RouteCollector $r)
    {
        $this->r = $r;
        $this->queryParameters = \routes\Router::create()->getQueryParameters();

        $this->mainPage();
        //$this->pathPage();
    }

    /** Returns page from view */
    private function getPage(string $pagePath)
    {
        ob_start();

        require $pagePath;
        $page = ob_get_contents();

        ob_end_clean();

        return $page;
    }

    /** Main page controller */
    private function mainPage()
    {
        $this->r->get('/', function ($vars) {
            throw new Exception('Error happens');
            
            return $this->getPage(PAGES['main']['path']);
        });
    }

    private function pathPage()
    {
        $this->r->get('/path{p: /?}', function ($vars) {
            return 'Path';
        });
        $this->r->get('/path/{number:\d+}', function ($vars) {
            $parameter = $this->queryParameters['param1'] ?? '';
            
            return "Path with number: {$vars['number']}, $parameter";
        });
    }

}
