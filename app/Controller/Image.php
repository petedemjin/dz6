<?php


namespace App\Controller;

use Intervention\Image\ImageManagerStatic as IImage;

class Image
{

    public function imageAction()
    {
        // open an image file
        $path = __DIR__ . '/../../images/picture.jpg';
        $result = __DIR__ . '/../../images/new_picture.jpg';
        $img = IImage::make($path)
            ->resize(200, null, function ($image) {
                $image->aspectRatio();
            });

        $img->save($result, 100);
        $img->text(
            "Цветы\n эфимерны",
            15,
            15,
            function ($font) {
                $font->file(__DIR__ . '/../../font/' . 'Laptev_Brush.otf')->size('28'); //требуется расширение freetype
                $font->color('330099');
                $font->align('left');
                $font->valign('top');
            });

        return  $img->response('jpg');
    }
}