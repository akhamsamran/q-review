<?php
namespace Edu\Cnm\Faqt;

require_once("autoload.php");
/**
 * JsonObjectStorage Class
 *
 * This class adds JsonSerializable to SplObjectStorage, allowing for the stored data to be json serialized. This lets the data be gotten in the interactions between frontend and backend in the RESTful apis.
 *
 *@author Tristan Bennett <tbennett19@cnm.edu>
 *@author Dylan McDonald <dmcdonald21@cnm.edu>
 *
 **/
class JsonObjectStorage extends \SplObjectStorage implements \JsonSerializable {
	public function jsonSerialize() {
		$fields = [];
		foreach($this as $object) {
			$fields[] = $object;
			$object->info = $this[$object];
		}
		return ($fields);
	}
}