<?php

namespace SkyWebDev\Database\Seeders;

use Illuminate\Database\Seeder;
use SkyWebDev\DbMail\BladeTemplate;

class AddBladeTemplatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $mail_path = app_path('Mail');
        $files = array_diff(scandir($mail_path), array('.', '..'));
        foreach ($files as $file) {
            $fileParts = explode('.', $file);
            $className = 'App\\Mail\\' . $fileParts[0];
            $full_path = $mail_path . '/' . $file;
            $content = file_get_contents($full_path);
            $viewName = $this->findViewName($content);
            if (empty($viewName)) {
                continue;
            }
            $viewParts = explode('.', $viewName);
            $template_path = resource_path('views/' . implode('/', $viewParts) . '.blade.php');
            $subject = $this->findSubject($content);;
            $body = file_get_contents($template_path);
            BladeTemplate::query()
                ->updateOrCreate(
                    ['class_name' => $className],
                    [
                        'template_path' => $template_path,
                        'template_name' => end($viewParts),
                        'subject' => $subject,
                        'body' => $body,
                    ]
                );
        }
    }

    /**
     * @param string $content
     * @return string
     */
    public function findViewName(string $content): string
    {
        $view_name = '';
        if (preg_match('/(?:markdown|view): \'(.*)\'/', $content, $matches) !== false) {
            if (isset($matches[1])) {
                $view_name = $matches[1];
            }
        }
        if (empty($view_name) && preg_match('/(?:markdown|view)\(\'(.*)\'\)/', $content, $matches) !== false) {
            if (isset($matches[1])) {
                $view_name = $matches[1];
            }
        }
        return $view_name;
    }

    /**
     * @param string $content
     * @return string
     */
    public function findSubject(string $content): string
    {
        $subject = '';
        if (preg_match('/subject: \'(.*)\'/', $content, $matches) !== false) {
            if (isset($matches[1])) {
                $subject = $matches[1];
            }
        }
        if (empty($view_name) && preg_match('/subject\(\'(.*)\'\)/', $content, $matches) !== false) {
            if (isset($matches[1])) {
                $subject = $matches[1];
            }
        }
        return $subject;
    }
}
