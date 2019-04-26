# cli-sass2css

CLI to compile SASS files using [scssphp Library](https://github.com/leafo/scssphp).

I created this library to compile a project's SASS files from a deployment pipeline.

## Usage

Create a script in the project's `composer.json`:

    "scripts": {
      "compile-sass": "sass2css assets/src/sass/ assets/dist/css/",
    }

From the pipeline run:

    composer run-script compile-sass

## Options

**--formatter=value**

    sass2css assets/src/sass/ assets/dist/css/ --formatter=nested

By default all output files will be formatted with SCSSPHP's Crunched formatter but we can specify any of the other available options:

 * compact = Leafo\ScssPhp\Formatter\Compact
 * compressed = Leafo\ScssPhp\Formatter\Compressed
 * crunched = Leafo\ScssPhp\Formatter\Crunched (default)
 * expanded = Leafo\ScssPhp\Formatter\Expanded
 * nested = Leafo\ScssPhp\Formatter\Nested

**--suffix=value**

Add a `.min` suffix to all output files, so `styles.sass` becomes `styles.min.sass`:

    sass2css assets/src/sass/ assets/dist/css/ --suffix=.min

**--verbose**

List processed files.

    sass2css assets/src/sass/ assets/dist/css/ --verbose
