<?php

$dirs = ['var', 'vendor'];
$finder = (new PhpCsFixer\Finder())
	->in(__DIR__)
	->exclude($dirs)
	->notPath('src/Kernel.php');

$rules = [
	'@Symfony' => true,
	'declare_strict_types' => true,
	'line_ending' => false
];

return (new PhpCsFixer\Config())
	->setRules($rules)
	->setRiskyAllowed(true)
	->setFinder($finder);
