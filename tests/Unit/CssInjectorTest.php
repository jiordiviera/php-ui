<?php

use Illuminate\Filesystem\Filesystem;
use Jiordiviera\PhpUi\Core\Transformer\CssInjector;

beforeEach(function () {
    $this->fs = new Filesystem;
    $this->tempCss = __DIR__.'/test.css';
    $this->fs->put($this->tempCss, "/* test */\n@theme {\n  --old: #000;\n}");
});

afterEach(function () {
    $this->fs->delete($this->tempCss);
});

test('it injects css variables into existing @theme block', function () {
    $injector = new CssInjector;
    $vars = ['--ui-primary' => '#fff'];

    $injector->injectVars($this->tempCss, $vars);

    $content = $this->fs->get($this->tempCss);
    expect($content)->toContain('--ui-primary: #fff;')
        ->and($content)->toContain('--old: #000;');
});

test('it creates @theme block if not exists', function () {
    $this->fs->put($this->tempCss, '/* no theme */');
    $injector = new CssInjector;

    $injector->injectVars($this->tempCss, ['--new' => 'blue']);

    $content = $this->fs->get($this->tempCss);
    expect($content)->toContain('@theme {')
        ->and($content)->toContain('--new: blue;');
});
