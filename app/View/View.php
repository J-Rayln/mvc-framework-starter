<?php /** @noinspection HtmlUnknownTarget */

namespace JonathanRayln\UdemyClone\View;

use JonathanRayln\UdemyClone\Application;

class View
{
    /**
     * Default layout to be used.
     */
    public const  DEFAULT_LAYOUT = 'default';

    /**
     * File extension to be used for template files.
     */
    public const TEMPLATE_EXTENSION = '.phtml';

    /** @var string|mixed */
    private string $appName;

    /**
     * Styles to be added to the <head></head> of a rendered view.
     *
     * @var array
     * */
    private array $styles = [];

    /**
     * JavaScript to be added to the <head></head> or before the closing
     * </body> of a rendered view.
     *
     * @var array
     */
    private array $scripts = [];

    public function __construct()
    {
        $this->appName = $_ENV['APP_NAME'];
    }

    /**
     * Renders the fully composed template view.  {{content}} and {{title}}
     * template tags are replaced with the page title passed to the render
     * method and the view templated called.
     *
     * If $title is provided, the value is passed through the $params array
     * and assigned a variable name of $pageTitle which can be called in the
     * template views to render the page title passed down through the
     * render method from the controller.  If null is provided for $title,
     * the variable is still created but with a null value.
     *
     * @param string      $template Template file to include as the {{content}} of
     *                              the layout view.
     * @param string|null $title    Optional. The title of the page that is
     *                              displayed in the &lt;title>&gt;&lt;/title&gt;
     *                              tags of the HTML markup.
     * @param array       $params   Optional array of parameters to be passed to
     *                              the $template.
     * @return bool|string
     */
    public function renderTemplate(string $template, ?string $title = null, array $params = []): bool|string
    {
        $params = array_merge($params, ['pageTitle' => $title]);
        $titleString = $this->renderPageTitle($title);
        $stylesContent = $this->stylesContent();
        $scriptsHeaderContent = $this->scriptsHeaderContent();
        $scriptsFooterContent = $this->scriptsFooterContent();
        $templateContent = $this->renderOnlyContent($template, $params);
        $layoutContent = $this->layoutContent();

        return str_replace(
            ['{{content}}', '{{title}}', '{{styles}}', '{{header_scripts}}', '{{footer_scripts}}'],
            [$templateContent, $titleString, $stylesContent, $scriptsHeaderContent, $scriptsFooterContent],
            $layoutContent
        );
    }

    /**
     * Renders the layout wrapper for the fully composed view.
     *
     * @return bool|string
     */
    protected function layoutContent(): bool|string
    {
        $layout = Application::$app->layout;
        if (Application::$app->controller) {
            $layout = Application::$app->controller->layout;
        }

        ob_start();
        include_once TEMPLATE_PATH . 'layouts/' . $layout . self::TEMPLATE_EXTENSION;
        return ob_get_clean();
    }

    /**
     * Renders just the content that will replace the {{content}} tag in the
     * layout view template.
     *
     * @param string $template Template to be called.  Do not include the leading
     *                         slash or the file extension.
     * @param array  $params   Optional associative array of parameters to be
     *                         passed to the final composed template rendering.
     * @return bool|string
     */
    protected function renderOnlyContent(string $template, array $params): bool|string
    {
        foreach ($params as $key => $value) {
            $$key = $value;
        }

        ob_start();
        include_once TEMPLATE_PATH . $template . self::TEMPLATE_EXTENSION;
        return ob_get_clean();
    }

    /**
     * Renders only the page title passed to the render method.
     *
     * @param string|null $title
     * @return string|null
     */
    protected function renderOnlyTitle(?string $title): ?string
    {
        return $title;
    }

    /**
     * Renders the full page title, if one is provided.  If no title is passed,
     * the site name is rendered alone.
     *
     * @param string|null $title Page title to be rendered
     * @return string
     */
    protected function renderPageTitle(?string $title): string
    {
        $titleString = $title ?? '';
        $separator = $title ? ' | ' : '';

        return $titleString . $separator . $this->appName;
    }

    /**
     * Registers a stylesheet for inclusion in the page header.
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
        $this->styles[] = [
            'handle' => $handle,
            'src'    => $src,
            'media'  => $media
        ];
    }

    /**
     * Renders a complete link for each stylesheet in the $styles array.
     *
     * @return bool|string
     */
    protected function stylesContent(): bool|string
    {
        ob_start();
        foreach ($this->styles as $style) {
            echo sprintf(
                '<link rel="stylesheet" id="%s" href="%s" type="text/css" media="%s" />' . "\n\t",
                $style['handle'],
                $style['src'],
                $style['media']
            );
        }
        return ob_get_clean();
    }

    /**
     * Registers a JavaScript file to the $scripts array.
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
        $this->scripts[] = [
            'handle'    => $handle,
            'src'       => $src,
            'in_footer' => $in_footer
        ];
    }

    /**
     * Renders a complete script tag for inclusion in the footer of the template.
     *
     * @return bool|string
     */
    protected function scriptsFooterContent(): bool|string
    {
        ob_start();
        foreach ($this->scripts as $script) {
            if ($script['in_footer'] === true) {
                echo sprintf(
                    '<script id="%s" src="%s"></script>' . "\n\t",
                    $script['handle'],
                    $script['src']
                );
            }
        }
        return ob_get_clean();
    }

    /**
     * Renders a complete script tag for inclusion in the header of the template.
     *
     * @return bool|string
     */
    protected function scriptsHeaderContent(): bool|string
    {
        ob_start();
        foreach ($this->scripts as $script) {
            if ($script['in_footer'] === false) {
                echo sprintf(
                    '<script id="%s" src="%s"></script>' . "\n\t",
                    $script['handle'],
                    $script['src']
                );
            }
        }
        return ob_get_clean();
    }
}