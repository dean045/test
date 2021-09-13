<?php

namespace App\Services;
use Symfony\Component\Yaml\Yaml;

class OrganizationService
{
	public function getAll($dir) {
		$organizations = Yaml::parseFile($dir . '/content/organizations.yaml');
		return $organizations['organizations'];
	}

	public function save($organizations,$dir) {
		$array['organizations'] = $organizations;
		$yaml = Yaml::dump($array);
        file_put_contents($dir . '/content/organizations.yaml', $yaml);
	}

    public function getById($id, $dir) {
        $organizations = $this->getAll($dir);
		return $organizations[$id];
	}

	public function Edit($id, $organization, $dir) {
        $organizations = $this->getAll($dir);
		$organizations[$id] = $organization;
		$this->save($organizations, $dir);
	}

	public function Add($organization, $dir) {
        $organizations = $this->getAll($dir);
		array_push($organizations, $organization->to_array());
		$this->save($organizations, $dir);
	}

	public function Delete($id, $dir) {
        $organizations = $this->getAll($dir);
		$len = count($organizations);
		$temp = [];
		$y = 0;
        $i = 0;
        while($i < $len - 1 && $y < $len)
        {
            //dd($id, $i, $y);
            if($y != $id)
            {
                $temp[$i] = $organizations[$y];
                $y++;
                $i++;
            }
            else
                $y++;
        } 
		$this->save($temp, $dir);
	}

	public function to_array($data)
    {
        if (is_array($data) || is_object($data))
        {
            $result = [];
            foreach ($data as $key => $value)
            {
                $result[$key] = (is_array($data) || is_object($data)) ? to_array($value) : $value;
            }
            return $result;
        }
        return $data;
    }
}