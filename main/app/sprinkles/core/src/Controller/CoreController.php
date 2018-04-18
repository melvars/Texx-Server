<?php
/**
 * UserFrosting (http://www.userfrosting.com)
 *
 * @link      https://github.com/userfrosting/UserFrosting
 * @license   https://github.com/userfrosting/UserFrosting/blob/master/licenses/UserFrosting.md (MIT License)
 */

namespace UserFrosting\Sprinkle\Core\Controller;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\NotFoundException as NotFoundException;

/**
 * CoreController Class
 *
 * Implements some common sitewide routes.
 * @author Alex Weissman (https://alexanderweissman.com)
 * @see http://www.userfrosting.com/navigating/#structure
 */
class CoreController extends SimpleController
{
    /**
     * Renders the default home page for UserFrosting.
     *
     * By default, this is the page that non-authenticated users will first see when they navigate to your website's root.
     * Request type: GET
     */
    public function pageIndex($request, $response, $args) {
        return $this->ci->view->render($response, 'pages/index.html.twig');
    }

    /**
     * Renders a sample "about" page for UserFrosting.
     *
     * Request type: GET
     */
    public function pageAbout($request, $response, $args) {
        return $this->ci->view->render($response, 'pages/about.html.twig');
    }

    /**
     * Renders terms of service page.
     *
     * Request type: GET
     */
    public function pageLegal($request, $response, $args) {
        return $this->ci->view->render($response, 'pages/legal.html.twig');
    }

    /**
     * Renders privacy page.
     *
     * Request type: GET
     */
    public function pagePrivacy($request, $response, $args) {
        return $this->ci->view->render($response, 'pages/privacy.html.twig');
    }

    /**
     * Render the alert stream as a JSON object.
     *
     * The alert stream contains messages which have been generated by calls to `MessageStream::addMessage` and `MessageStream::addMessageTranslated`.
     * Request type: GET
     */
    public function jsonAlerts($request, $response, $args) {
        return $response->withJson($this->ci->alerts->getAndClearMessages());
    }

    /**
     * Handle all requests for raw assets.
     * Request type: GET
     */
    public function getAsset($request, $response, $args) {
        // By starting this service, we ensure that the timezone gets set.
        $config = $this->ci->config;

        $assetLoader = $this->ci->assetLoader;

        if (!$assetLoader->loadAsset($args['url'])) {
            throw new NotFoundException($request, $response);
        }

        return $response
            ->withHeader('Content-Type', $assetLoader->getType())
            ->withHeader('Content-Length', $assetLoader->getLength())
            ->write($assetLoader->getContent());
    }
}
