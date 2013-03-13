<?php
/**
*       ETextImage - This widget allows you to display the text as an image.
*
*       @author Vladimir Papaev <kosenka@gmail.com>
*       @version 0.1
*
*       usage:
*
*       1) Override CController::actions() and register an action of class ETextImageAction with ID 'textImage':
*	public function actions()
*	{
*		return array(
*			'textImage'=>array(
*				'class'=>'application.extensions.ETextImage.ETextImageAction',
*			),
*		);
*	}
*
*       2) In the controller view, insert a widget in the form.
*       $this->widget('application.extensions.ETextImage.ETextImage',
*                        array(
*                              'text' => "(495)1234567",
*                              'fontSize' => 10,
*                              'fontFile' => 'tahoma.ttf',
*                              'transparent'=>false,
*                              'foreColor'=>0x2040A0,
*                              'backColor'=>0x55FF00,
*                             )
*                       );
*
**/

class ETextImageAction extends CAction
{
	/**
	 * @var string
	 */
        public $text;
        
	/**
	 * @var integer the font size. Default to 8.
	 */
        public $fontSize=8;

	/**
	 * @var integer the background color. For example, 0x55FF00.
	 * Defaults to 0xFFFFFF, meaning white color.
	 */
	public $backColor=0xFFFFFF;

	/**
	 * @var integer the font color. For example, 0x55FF00. Defaults to 0x000000 (black color).
	 */
	public $foreColor=0x000000;

	/**
	 * @var boolean whether to use transparent background. Defaults to false.
	 */
	public $transparent=false;

	/**
	 * @var Font filename. Defaults to 'arial.ttf'.
	 */
	public $fontFile='arial';

	public function run()
	{
                $this->text        = Yii::app()->request->getQuery('text', 'ETextImage');
                $this->text        = urldecode(base64_decode($this->text));
                
	        $this->transparent = Yii::app()->request->getQuery('transparent', 1);
                $this->backColor   = Yii::app()->request->getQuery('backColor', $this->backColor);
                $this->foreColor   = Yii::app()->request->getQuery('foreColor', $this->foreColor);
	        $this->fontSize    = Yii::app()->request->getQuery('fontSize', $this->fontSize);

                $this->fontFile = Yii::app()->request->getQuery('fontFile', $this->fontFile);
                $this->fontFile = dirname(__FILE__).DIRECTORY_SEPARATOR.'fonts'.DIRECTORY_SEPARATOR.$this->fontFile.'.ttf';
                if(!file_exists($this->fontFile))
                {
                        throw new CException('ETextImage: Font "'.$this->fontFile.'" not found.');
                        Yii::app()->end;
                }

                $im=$this->createImage();

                // Output to browser
		header('Pragma: public');
		header('Expires: 0');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Content-Transfer-Encoding: binary');
		header("Content-type: image/png");
                imagepng($im);
                imagedestroy($im);
		Yii::app()->end();
	}

        protected function calculateTextBox($text, $font_file, $font_size, $font_angle)
        {
            $box = imagettfbbox($font_size, $font_angle, $font_file, $text);

            $min_x = min(array($box[0], $box[2], $box[4], $box[6]));
            $max_x = max(array($box[0], $box[2], $box[4], $box[6]));
            $min_y = min(array($box[1], $box[3], $box[5], $box[7]));
            $max_y = max(array($box[1], $box[3], $box[5], $box[7]));

            return array(
                'left' => ($min_x >= -1) ? -abs($min_x + 1) : abs($min_x + 2),
                'top' => abs($min_y),
                'width' => $max_x - $min_x,
                'height' => $max_y - $min_y,
                'box' => $box
            );
        }

        protected function createImage()
        {
                $box = $this->calculateTextBox($this->text,$this->fontFile,$this->fontSize,0);
                $im=imagecreatetruecolor($box['width'],$box['height']);

		$backColor=imagecolorallocate($im,
			(int)($this->backColor%0x1000000/0x10000),
			(int)($this->backColor%0x10000/0x100),
			$this->backColor%0x100);

                imagefilledrectangle($im, 0, 0, $box['width'], $box['height'], $backColor);
                if($this->transparent) imagecolortransparent($im,$backColor);

		$foreColor=imagecolorallocate($im,
			(int)($this->foreColor%0x1000000/0x10000),
			(int)($this->foreColor%0x10000/0x100),
			$this->foreColor%0x100);

                imagettftext($im, $this->fontSize, 0, 0, $this->fontSize, $foreColor, $this->fontFile, $this->text);

                return $im;
        }
}
