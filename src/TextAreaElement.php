<?php

namespace ImpressCMS\Modules\CodeMirrorIntegration;

/**
 * Defines extended TextAreaElement
 *
 * @package ImpressCMS\Modules\CodeMirrorIntegration
 */
class TextAreaElement extends \icms_form_elements_Textarea {

    /**
     * Editor width
     *
     * @var string
     */
    private $width = "100%";

    /**
     * Editor height
     *
     * @var string
     */
    private $height = "400px";

    /**
     * @inheritDoc
     */
    public function __construct($configs, $checkCompatible = false) {
        if (is_array($configs)) {
            $vars = array_keys(get_object_vars($this));
            foreach ($configs as $key => $val) {
                if (in_array("_" . $key, $vars, true)) {
                    $this->{"_" . $key} = $val;
                } elseif (in_array($key, array('name', 'value'))) {
                    $method = "set" . ucfirst($key);
                    $this->$method($val);
                } else {
                    $this->config[$key] = $val;
                }
            }
        }

        parent::__construct("", $this->getName(), $this->getValue());
        parent::setExtra("style='width:" . $this->width . ";height:" . $this->height . ";'");
    }

    /**
     * @inheritDoc
     */
    public function render() {
        $ret = parent::render();

        // take xml for html rendering
        if ($this->config['syntax'] == 'html') {
            $this->config['syntax'] = 'xml';
        }

        $css = array();
        $js = array();
        $this->config['syntax'] = (!isset($this->config['syntax'])?'php':$this->config['syntax']);
        switch ($this->config['syntax']) {
            case 'php':
                $js[] = '"../contrib/' . $this->config['syntax'] . '/js/tokenizephp.js"';
            case 'lua':
            case 'python':
                $css[] = '"' . ICMS_URL . $this->rootpath . '/editor/contrib/' . $this->config['syntax'] . '/css/' . $this->config['syntax'] . 'colors.css"';
                $js[] = '"../contrib/' . $this->config['syntax'] . '/js/parse' . $this->config['syntax'] . '.js"';
                break;
            case 'xml':
            case 'css':
            case 'javascript':
            case 'js':
            case 'sparql':
                if ($this->config['syntax'] == 'javascript') {
                    $this->config['syntax'] = 'js';
                }
                $js[] = '"parse' . $this->config['syntax'] . '.js"';
                $css[] = '"' . ICMS_URL . $this->rootpath . '/editor/css/' . $this->config['syntax'] . 'colors.css"';
                break;
            case 'mixed':
                $js[] = '"parsexml.js"';
                $js[] = '"parsecss.js"';
                $js[] = '"tokenizejavascript.js"';
                $js[] = '"parsejavascript.js"';
                $js[] = '"parsehtmlmixed.js"';
                $css[] = '"' . ICMS_URL . $this->rootpath . '/editor/css/csscolors.css"';
                $css[] = '"' . ICMS_URL . $this->rootpath . '/editor/css/jscolors.css"';
                $css[] = '"' . ICMS_URL . $this->rootpath . '/editor/css/xmlcolors.css"';
                break;
        }
        $css[] = '"' . ICMS_URL . $this->rootpath . '/editor/css/docs.css"';

        if (isset($this->config["is_editable"])) {
            if ($this->config["is_editable"]) {
                $readonly = 'false';
            } else {
                $readonly = 'true';
            }
        } else {
            $readonly = 'false';
        }
        $ret .= '
		<script src="' . ICMS_URL . $this->rootpath . '/editor/js/codemirror.js" type="text/javascript"></script>
		<script type="text/javascript">
		  var editor = CodeMirror.fromTextArea(\'' . $this->getName() . '_tarea\', {
		  	width: "' . $this->width . '",
    		height: "' . $this->height . '",
    		parserfile: [' . implode(',', $js) . '],
    		[' . implode(',', $css) . '],
    		"' . ICMS_URL . $this->rootpath . '/editor/js/",
			lineNumbers: true,
			continuousScanning: 500,
			textWrapping: false,
			readOnly: ' . $readonly . '
  		})
		</script>
		<link rel="stylesheet" type="text/css" media="all" href="' . ICMS_URL . $this->rootpath . '/css/editor.css" />';

        return $ret;
    }
}