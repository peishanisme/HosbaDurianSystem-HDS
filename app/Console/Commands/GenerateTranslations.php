<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\LibreTranslateService;

class GenerateTranslations extends Command
{
    protected $signature = 'translate:generate {locale}';
    protected $description = 'Generate translated language files using LibreTranslate';

    public function handle()
    {
        $locale = $this->argument('locale');
        $validLocales = ['zh', 'ms'];

        if (!in_array($locale, $validLocales)) {
            $this->error("Invalid locale. Use zh or ms.");
            return;
        }

        $translator = app(LibreTranslateService::class);

        $sourceFile = lang_path('en/messages.php');
        $targetFile = lang_path("$locale/messages.php");

        $strings = include $sourceFile;
        $translated = [];

        foreach ($strings as $key => $text) {
            $this->info("Translating [$key]...");
            $translated[$key] = $translator->translate($text, $locale);
            sleep(1); // avoid rate limit
        }

        $content = "<?php\n\nreturn " . var_export($translated, true) . ";\n";
        file_put_contents($targetFile, $content);

        $this->info("Translation generated: $targetFile");
    }
}
