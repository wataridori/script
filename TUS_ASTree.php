<?php

	interface TUS_ASTree {
		function child($i);
		function numChildren();
		function children();
		function location();
	}