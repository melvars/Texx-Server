<?php
/**
 * UserFrosting (http://www.userfrosting.com)
 *
 * @link      https://github.com/userfrosting/UserFrosting
 * @license   https://github.com/userfrosting/UserFrosting/blob/master/licenses/UserFrosting.md (MIT License)
 */

namespace UserFrosting\Sprinkle\Core\Util;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Http\Body;

/**
 * Performs pre-flight tests on your server environment to check that it meets the requirements.
 *
 * @author Alex Weissman (https://alexanderweissman.com)
 */
class CheckEnvironment
{
    /**
     * @var \RocketTheme\Toolbox\ResourceLocator\UniformResourceLocator Locator service for stream resources.
     */
    protected $locator;

    /**
     * @var array The results of any failed checks performed.
     */
    protected $resultsFailed = [];

    /**
     * @var array The results of any successful checks performed.
     */
    protected $resultsSuccess = [];

    /**
     * @var \Slim\Views\Twig The view object, needed for rendering error page.
     */
    protected $view;

    /**
     * @var \Illuminate\Cache\CacheManager Cache service for cache access.
     */
    protected $cache;

    /**
     * Constructor.
     *
     * @param $view \Slim\Views\Twig The view object, needed for rendering error page.
     * @param $locator \RocketTheme\Toolbox\ResourceLocator\UniformResourceLocator Locator service for stream resources.
     */
    public function __construct($view, $locator, $cache) {
        $this->view = $view;
        $this->locator = $locator;
        $this->cache = $cache;
    }

    /**
     * Invoke the CheckEnvironment middleware, performing all pre-flight checks and returning an error page if problems were found.
     *
     * @param  \Psr\Http\Message\ServerRequestInterface $request PSR7 request
     * @param  \Psr\Http\Message\ResponseInterface $response PSR7 response
     * @param  callable $next Next middleware
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke($request, $response, $next) {
        $problemsFound = FALSE;

        // If production environment and no cached checks, perform environment checks
        if ($this->isProduction() && $this->cache->get('checkEnvironment') != 'pass') {
            $problemsFound = $this->checkAll();

            // Cache if checks passed
            if (!$problemsFound) {
                $this->cache->forever('checkEnvironment', 'pass');
            }
        } else if (!$this->isProduction()) {
            $problemsFound = $this->checkAll();
        }

        if ($problemsFound) {
            $results = array_merge($this->resultsFailed, $this->resultsSuccess);

            $response = $this->view->render($response, 'pages/error/config-errors.html.twig', [
                "messages" => $results
            ]);
        } else {
            $response = $next($request, $response);
        }

        return $response;
    }

    /**
     * Run through all pre-flight checks.
     */
    public function checkAll() {
        $problemsFound = FALSE;

        if ($this->checkApache()) $problemsFound = TRUE;

        if ($this->checkPhp()) $problemsFound = TRUE;

        if ($this->checkPdo()) $problemsFound = TRUE;

        if ($this->checkGd()) $problemsFound = TRUE;

        if ($this->checkImageFunctions()) $problemsFound = TRUE;

        if ($this->checkPermissions()) $problemsFound = TRUE;

        return $problemsFound;
    }

    /**
     * For Apache environments, check that required Apache modules are installed.
     */
    public function checkApache() {
        $problemsFound = FALSE;

        // Perform some Apache checks.  We may also need to do this before any routing takes place.
        if (strpos(php_sapi_name(), 'apache') !== FALSE) {

            $require_apache_modules = ['mod_rewrite'];
            $apache_modules = apache_get_modules();

            $apache_status = [];

            foreach ($require_apache_modules as $module) {
                if (!in_array($module, $apache_modules)) {
                    $problemsFound = TRUE;
                    $this->resultsFailed['apache-' . $module] = [
                        "title" => "<i class='fa fa-server fa-fw'></i> Missing Apache module <b>$module</b>.",
                        "message" => "Please make sure that the <code>$module</code> Apache module is installed and enabled.  If you use shared hosting, you will need to ask your web host to do this for you.",
                        "success" => FALSE
                    ];
                } else {
                    $this->resultsSuccess['apache-' . $module] = [
                        "title" => "<i class='fa fa-server fa-fw'></i> Apache module <b>$module</b> is installed and enabled.",
                        "message" => "Great, we found the <code>$module</code> Apache module!",
                        "success" => TRUE
                    ];
                }
            }
        }

        return $problemsFound;
    }

    /**
     * Check for GD library (required for Captcha).
     */
    public function checkGd() {
        $problemsFound = FALSE;

        if (!(extension_loaded('gd') && function_exists('gd_info'))) {
            $problemsFound = TRUE;
            $this->resultsFailed['gd'] = [
                "title" => "<i class='fa fa-image fa-fw'></i> GD library not installed",
                "message" => "We could not confirm that the <code>GD</code> library is installed and enabled.  GD is an image processing library that UserFrosting uses to generate captcha codes for user account registration.",
                "success" => FALSE
            ];
        } else {
            $this->resultsSuccess['gd'] = [
                "title" => "<i class='fa fa-image fa-fw'></i> GD library installed!",
                "message" => "Great, you have <code>GD</code> installed and enabled.",
                "success" => TRUE
            ];
        }

        return $problemsFound;
    }

    /**
     * Check that all image* functions used by Captcha exist.
     *
     * Some versions of GD are missing one or more of these functions, thus why we check for them explicitly.
     */
    public function checkImageFunctions() {
        $problemsFound = FALSE;

        $funcs = [
            'imagepng',
            'imagecreatetruecolor',
            'imagecolorallocate',
            'imagefilledrectangle',
            'imageline',
            'imagesetpixel',
            'imagefontwidth',
            'imagestring'
        ];

        foreach ($funcs as $func) {
            if (!function_exists($func)) {
                $problemsFound = TRUE;
                $this->resultsFailed['function-' . $func] = [
                    "title" => "<i class='fa fa-code fa-fw'></i> Missing image manipulation function.",
                    "message" => "It appears that function <code>$func</code> is not available.  UserFrosting needs this to render captchas.",
                    "success" => FALSE
                ];
            } else {
                $this->resultsSuccess['function-' . $func] = [
                    "title" => "<i class='fa fa-code fa-fw'></i> Function <b>$func</b> is available!",
                    "message" => "Sweet!",
                    "success" => TRUE
                ];
            }
        }

        return $problemsFound;
    }

    /**
     * Check that PDO is installed and enabled.
     */
    public function checkPdo() {
        $problemsFound = FALSE;

        if (!class_exists('PDO')) {
            $problemsFound = TRUE;
            $this->resultsFailed['pdo'] = [
                "title" => "<i class='fa fa-database fa-fw'></i> PDO is not installed.",
                "message" => "I'm sorry, you must have PDO installed and enabled in order for UserFrosting to access the database.  If you don't know what PDO is, please see <a href='http://php.net/manual/en/book.pdo.php'>http://php.net/manual/en/book.pdo.php</a>.",
                "success" => FALSE
            ];
        } else {
            $this->resultsSuccess['pdo'] = [
                "title" => "<i class='fa fa-database fa-fw'></i> PDO is installed!",
                "message" => "You've got PDO installed.  Good job!",
                "success" => TRUE
            ];
        }

        return $problemsFound;
    }

    /**
     * Check that log, cache, and session directories are writable, and that other directories are set appropriately for the environment.
     */
    function checkPermissions() {
        $problemsFound = FALSE;

        $shouldBeWriteable = [
            $this->locator->findResource('log://') => TRUE,
            $this->locator->findResource('cache://') => TRUE,
            $this->locator->findResource('session://') => TRUE
        ];

        if ($this->isProduction()) {
            // Should be write-protected in production!
            $shouldBeWriteable = array_merge($shouldBeWriteable, [
                \UserFrosting\SPRINKLES_DIR => FALSE,
                \UserFrosting\VENDOR_DIR => FALSE
            ]);
        }

        // Check for essential files & perms
        foreach ($shouldBeWriteable as $file => $assertWriteable) {
            $is_dir = FALSE;
            if (!file_exists($file)) {
                $problemsFound = TRUE;
                $this->resultsFailed['file-' . $file] = [
                    "title" => "<i class='fa fa-file-o fa-fw'></i> File or directory does not exist.",
                    "message" => "We could not find the file or directory <code>$file</code>.",
                    "success" => FALSE
                ];
            } else {
                $writeable = is_writable($file);
                if ($assertWriteable !== $writeable) {
                    $problemsFound = TRUE;
                    $this->resultsFailed['file-' . $file] = [
                        "title" => "<i class='fa fa-file-o fa-fw'></i> Incorrect permissions for file or directory.",
                        "message" => "<code>$file</code> is "
                            . ($writeable ? "writeable" : "not writeable")
                            . ", but it should "
                            . ($assertWriteable ? "be writeable" : "not be writeable")
                            . ".  Please modify the OS user or group permissions so that user <b>"
                            . exec('whoami') . "</b> "
                            . ($assertWriteable ? "has" : "does not have") . " write permissions for this directory.",
                        "success" => FALSE
                    ];
                } else {
                    $this->resultsSuccess['file-' . $file] = [
                        "title" => "<i class='fa fa-file-o fa-fw'></i> File/directory check passed!",
                        "message" => "<code>$file</code> exists and is correctly set as <b>"
                            . ($writeable ? "writeable" : "not writeable")
                            . "</b>.",
                        "success" => TRUE
                    ];
                }
            }
        }
        return $problemsFound;
    }

    /**
     * Check that PHP meets the minimum required version.
     */
    public function checkPhp() {
        $problemsFound = FALSE;

        // Check PHP version
        if (version_compare(phpversion(), \UserFrosting\PHP_MIN_VERSION, '<')) {
            $problemsFound = TRUE;
            $this->resultsFailed['phpVersion'] = [
                "title" => "<i class='fa fa-code fa-fw'></i> You need to upgrade your PHP installation.",
                "message" => "I'm sorry, UserFrosting requires version " . \UserFrosting\PHP_MIN_VERSION . " or greater.  Please upgrade your version of PHP, or contact your web hosting service and ask them to upgrade it for you.",
                "success" => FALSE
            ];
        } else {
            $this->resultsSuccess['phpVersion'] = [
                "title" => "<i class='fa fa-code fa-fw'></i> PHP version checks out!",
                "message" => "You're using PHP " . \UserFrosting\PHP_MIN_VERSION . "or higher.  Great!",
                "success" => TRUE
            ];
        }

        return $problemsFound;
    }

    /**
     * Determine whether or not we are running in production mode.
     *
     * @return bool
     */
    public function isProduction() {
        return (getenv('UF_MODE') == 'production');
    }
}
