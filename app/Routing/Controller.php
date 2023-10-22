<?php

namespace JonathanRayln\UdemyClone\Routing;

use JonathanRayln\UdemyClone\Application;
use JonathanRayln\UdemyClone\Http\Request;
use JonathanRayln\UdemyClone\Http\Response;
use JonathanRayln\UdemyClone\Routing\Exceptions\BadMethodCallException;
use JonathanRayln\UdemyClone\View\View;

abstract class Controller
{
    public string $layout = View::DEFAULT_LAYOUT;

    public function __construct(protected Request $request, protected Response $response) {}

    /**
     * Renders the fully composed template view.
     *
     * @param string $template Template file to include as the {{content}} of
     *                         the layout view.
     * @param array  $params   Optional array of parameters to be passed to the
     *                         $template.
     * @return $this
     */
    public function render(string $template, ?string $title = null, array $params = []): static
    {
        echo Application::$app->view->renderTemplate($template, $title, $params);
        return $this;
    }

    /**
     * Sets the layout to be used instead of the default layout defined.  Call
     * in the action method of the controller with `$this->setLayout($layout)`
     * prior to rendering the template.
     *
     * @param string $layout
     * @return Controller
     */
    public function setLayout(string $layout): static
    {
        $this->layout = $layout;
        return $this;
    }

    /**
     * Helper function to register a stylesheet for inclusion in the page header.
     *
     * If registering more than one stylesheet, method calls should be listed in
     * order of dependency.
     *
     * @param string $handle Unique name for this stylesheet. This value is used
     *                       for the 'id=' attribute.
     * @param string $src    Full URL of the stylesheet, or path of the stylesheet
     *                       relative to the application's root directory.  Helper
     *                       functions can be used to parse paths.
     *                       E.g. asset_url('js/nameOfFile.js') will work.
     * @param string $media  The media for which this stylesheet has been defined.
     *                       Default 'all'. Accepts media types like 'all', 'print'
     *                       and 'screen', or media queries like '(orientation:
     *                       portrait)' and '(max-width: 640px)'.
     * @return void
     */
    public function registerStyle(string $handle, string $src, string $media = 'all'): void
    {
        Application::$app->view->registerStyle($handle, $src);
    }

    /**
     * Helper function that registers a JavaScript file to the $scripts array
     * for inclusion in building the completed template output.
     *
     * If registering more than one script, method calls should be listed in
     * order of dependency.
     *
     * @param string $handle    Unique name for the script.  This value is used
     *                          for the 'id=' attribute.
     * @param string $src       Full URL of the script, or path of the script
     *                          relative to the application's root directory.
     * @param bool   $in_footer Whether to print the script in the footer.
     *                          'true' prints the script in the <head></head>.
     *                          Default 'true'.
     * @return void
     */
    public function registerScript(string $handle, string $src, bool $in_footer = true): void
    {
        Application::$app->view->registerScript($handle, $src, $in_footer);
    }

    /**
     * Handle calls to methods that don't exist.
     *
     * @throws BadMethodCallException
     */
    public function __call(string $method, array $arguments)
    {
        throw new BadMethodCallException(sprintf(
            'Method %s::%s does not exist.', static::class, $method
        ));
    }
}