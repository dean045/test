<?php

namespace App\Services;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Organization;
use App\Entity\User;

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

	public function Edit($id, Request $request, $dir) {
		$users = [];
        $i = 0;
		$checkboxes = $request->request->get('check');

        while ($request->request->get('username'. ++$i))
        {
			if(!$checkboxes || !in_array($i, $checkboxes))
			{
				$user = new User(strip_tags($request->request->get('username'.$i)), 
					strip_tags($request->request->get('password'.$i)), 
					explode(';', strip_tags($request->request->get('role'.$i))));
				//dd($user);
				array_push($users, $user->to_array());
			}
        }
        $org = new Organization(strip_tags($request->request->get('name')),
            strip_tags($request->request->get('descri')), $users);
        $organizations = $this->getAll($dir);
		$organizations[$id] = $org->to_array();
		$this->save($organizations, $dir);
		//dd($organizations);
	}

	public function Add(Request $request, $dir) {
		$users = [];
        $i = -1;
        while ($request->request->get('username'. ++$i))
        {

			$user = new User(strip_tags($request->request->get('username'.$i)), 
				strip_tags($request->request->get('password'.$i)), 
				explode(';', strip_tags($request->request->get('role'.$i))));
			array_push($users, $user->to_array());
        }
        $org = new Organization(strip_tags($request->request->get('name')), strip_tags($request->request->get('descri')),$users);
        $organizations = $this->getAll($dir);
		array_push($organizations, $org->to_array());
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