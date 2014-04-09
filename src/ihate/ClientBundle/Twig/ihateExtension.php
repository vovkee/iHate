<?php

namespace ihate\ClientBundle\Twig;

use ihate\CoreBundle\Entity\Post;

class ihateExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
          new \Twig_SimpleFilter('timeAgo',array($this, 'timeAgo')),
        );
    }

    public function timeAgo(\DateTime $createdAt)
    {
        $currentTime 	= new \DateTime();
        $time_elapsed 	= $currentTime->getTimestamp() - $createdAt->getTimestamp();
        $seconds 	= $time_elapsed ;
        $minutes 	= round($time_elapsed / 60 );
        $hours 		= round($time_elapsed / 3600);
        $days 		= round($time_elapsed / 86400 );
        $weeks 		= round($time_elapsed / 604800);
        $months 	= round($time_elapsed / 2600640 );
        $years 		= round($time_elapsed / 31207680 );
        // Seconds
        if($seconds <= 60){
            return "$seconds seconds ago";
        }
        //Minutes
        else if($minutes <=60){
            if($minutes==1){
                return "one minute ago";
            }
            else{
                return "$minutes minutes ago";
            }
        }
        //Hours
        else if($hours <=24){
            if($hours==1){
                return "an hour ago";
            }
            else{
                return "$hours hours ago";
            }
        }
        //Days
        else if($days <= 7){
            if($days==1){
                return "yesterday";
            }
            else{
                return "$days days ago";
            }
        }
        //Weeks
        else if($weeks <= 4.3){
            if($weeks==1){
                return "a week ago";
            }
            else{
                return "$weeks weeks ago";
            }
        }
        //Months
        else if($months <=12){
            if($months==1){
                return "a month ago";
            }
            else{
                return "$months months ago";
            }
        }
        //Years
        else{
            if($years==1){
                return "one year ago";
            }
            else{
                return "$years years ago";
            }
        }
    }

    public function getName()
    {
        return 'ihate_extension';
    }
}