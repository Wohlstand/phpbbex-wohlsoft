<?php
namespace randomPictures\randomPictures\event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class listener implements EventSubscriberInterface
{
        protected $config;
        protected $template;
	/* @var \phpbb\user */
        protected $user;
        protected $root_path;
        protected $randHeader;
        protected $randFooter;
        protected $state;

        public function __construct(\phpbb\config\config $config, \phpbb\template\template $template, \phpbb\user $user, $root_path)
        {
                $this->config      = $config;
                $this->template      = $template;
                $this->user         = $user;
                $this->root_path   = $root_path;

                $path_to_random = dirname(__FILE__)."/../../../../styles/".$this->user->style['style_path'].'/template/random/';
                $this->state = $path_to_random;

                //ob_start();
                //var_dump($this->config);
                //$this->state = ob_get_clean();

                $randoms=1;
                $randoms_f = $path_to_random.'randoms_head.txt';
                if(file_exists($randoms_f))
                      $randoms=intval(file_get_contents($randoms_f));

                $randHeader = $path_to_random.'header_'.mt_rand(1, $randoms).'.html';
                if(file_exists($this->randHeader))
                {
                    $randHeader = file_get_contents($this->randHeader);
                    $randHeader = str_replace("{T_THEME_PATH}", "./styles/" . rawurlencode($this->user->style['style_path']) . '/theme', $this->randHeader);
                }
                else
                $this->randHeader = "";

                $randoms=1;
                $randoms_f = $path_to_random.'randoms_footer.txt';
                if(file_exists($randoms_f))
                        $randoms=intval(file_get_contents($randoms_f));

                $this->randFooter = $path_to_random.'footer_'.mt_rand(1, $randoms).'.html';
                if(file_exists($this->randFooter))
                        {
                                $this->randFooter = file_get_contents($this->randFooter);
                                $this->randFooter = str_replace("{T_THEME_PATH}", "./styles/" . rawurlencode($this->user->style['style_path']) . '/theme', $this->randFooter);
                        }
                else
                        $this->randFooter = "";
        }
   
        static public function getSubscribedEvents()
        {
                return array( 'core.page_header_after'   => 'bite_me' );
        }
   
        public function bite_me()
        {
                $this->template->assign_vars(array(
		        'T_RAND_HEADER'			=> $this->randHeader,
		        'T_RAND_FOOTER'			=> $this->randFooter,
		        'T_RAND_STATE'			=> $this->state,
                ));
        }
}

