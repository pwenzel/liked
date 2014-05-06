<?php

class Entry extends Eloquent {

    protected $table = 'entries';
    protected $fillable = array('url', 'title', 'description', 'pubdate');

	public function getDates()
	{
		return array('created_at', 'updated_at', 'liked_date', 'date_published', 'date');
	}

}