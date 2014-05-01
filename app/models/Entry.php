<?php

class Entry extends Eloquent {

    protected $table = 'entries';
    protected $fillable = array('url', 'title', 'description');

}