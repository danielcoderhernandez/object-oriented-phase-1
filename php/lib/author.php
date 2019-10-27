<?php

require_once('../Classes/Author.php');

use DanielCoderHernandez\ObjectOriented\Author;
use Ramsey\Uuid\Uuid;

$danny = new Author("f7286cc1-5d88-4a91-844e-588cbb940c67",
	"f6cac5f10e4d14bf1cf85cfec2a0a24c",
	"https://creativeimagelicensing.com/how-to-find-non-copyrighted-pictures/",
	"audialb@yahoo.com",
	"4a21312be53b16e88c5f78bcfddd16058f773cbc15ead7985b4dcb967dc85c698fcd080fe5ff93a9399c2003084abc3d8",
	"audialb"
);

echo($danny->getAuthorId());
echo($danny->getAuthorActivationToken());
echo($danny->getAuthorAvatarUrl());
echo($danny->getAuthorEmail());
echo($danny->getAuthorHash());
echo($danny->getAuthorUsername());
