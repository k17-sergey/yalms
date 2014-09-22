<?php

/**
 * Class User_teacher
 *
 * @property integer        $id
 * @property integer        $user_id
 * @property boolean        $enabled
 *
 */
class User_teacher extends \Eloquent
{
	protected $fillable = ['enabled'];

	protected $hidden = array('user_id', 'id');

	public function setEnabled($value)
	{
		$this->enabled = $value;
	}

	public function isEnabled()
	{
		return $this->enabled;
	}
}