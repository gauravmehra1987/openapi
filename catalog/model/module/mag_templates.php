<?php
class ModelModuleMagTemplates extends Model {
	public function getTemplates() {
		$template_data = array();

		$template_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "mag_templates");

		foreach ($template_query->rows as $template) {
			$template_data[] = array(
				'template_id' => $template['template_id'],
				'name'        => $template['name'],
				'group'       => $template['group'],
				'type'        => $template['type'],
				'description' => $template['description']
			);
		}

		return $template_data;
	}

	public function getMagTemplate($template_name) {
		$template_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "mag_templates WHERE name = '" . $template_name . "'");

		if ( $template_query->rows ) return $template_query->row; else return false;
	}
}