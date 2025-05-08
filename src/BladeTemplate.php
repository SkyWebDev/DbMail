<?php

namespace SkyWebDev\DbMail;

use Illuminate\Database\Eloquent\Model;

/**
 * Class BladeTemplate
 *
 * @property int $id
 * @property string $template_class
 * @property string $template_name
 * @property string $template_path
 * @property string $subject
 * @property string $body
 */
class BladeTemplate extends Model
{
    protected $table = 'blade_templates';

    protected $guarded = ['id'];
}
