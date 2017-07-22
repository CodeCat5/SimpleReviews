<?php

class MY_Loader extends CI_Loader
{
	public function template($template_name, $vars = array(), $return = FALSE)
	{
		if ($return) {
			$content = $this->view('header', $vars, $return);
			$content .= $this->view($template_name, $vars, $return);
			$content .= $this->view('footer', $vars, $return);
			return $content;
		} else {
			$this->view('header', $vars);
			$this->view($template_name, $vars);
			$this->view('footer', $vars);
		}
	}

	public function admin_template($template_name, $vars = array(), $return = FALSE)
	{
		$vars['template_name'] = $template_name;

		if ($return) {
			$content = $this->view('admin_header', $vars, $return);
			$content .= $this->view($template_name, $vars, $return);
			$content .= $this->view('admin_footer', $vars, $return);
			return $content;
		} else {
			$this->view('admin_header', $vars);
			$this->view($template_name, $vars);
			$this->view('admin_footer', $vars);
		}
	}

}

?>