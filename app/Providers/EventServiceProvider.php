<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Carbon;
use App\Models\Adoption;
use JeroenNoten\LaravelAdminLte\Events\BuildingMenu;
use Helper;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        Event::listen(BuildingMenu::class, function (BuildingMenu $event) {

            $notifications = Adoption::latest()->skip(0)->take(17)->get();

            $notification_items = [];
            foreach($notifications as $notification){
                $item = [
                    'text' => '',
                    'url' => '',
                    'icon' => '',
                    'label' => '',
                    'label_color' => '',
                ];
                $item['text'] = Helper::getAdoptionStatus()[$notification->status];
                $item['url'] = "/notifications/adoption-notifications/".$notification->id;
                $item['icon'] = Helper::getAdoptionIcon()[$notification->status].' mr-2 '.Helper::getAdoptionTextColor()[$notification->status];
                $item['label'] = $notification->created_at->diffForHumans();
                $item['label_color'] = (Carbon::parse($notification->created_at)->isToday() ? 'success' : 'light text-muted') . ' float-right';
                
                array_push($notification_items,$item);
            }

            $event->menu->addBefore('fullscreen-widget',[
                'text'        => '',
                'icon'        => 'fas fa-hand-holding-heart',
                'label'       => Adoption::whereDate('created_at', Carbon::today())->count(),
                'label_color' => 'success',
                'id'          => 'navbar-notifications',
                'key'         => 'navbar-notifications',
                'topnav_right' => true,
            ]);

            $event->menu->addIn('navbar-notifications', [
                'text' => 'Adoption Status Notification',
                'url' => '#',
                'classes' => 'dropdown-menu-xl dropdown-header',
            ]);

            $event->menu->addIn('navbar-notifications', ...$notification_items);

            $event->menu->addIn('navbar-notifications', [
                'text' => 'View All',
                'url' => '/notifications/adoption-notifications',
                'classes' => 'dropdown-footer',
            ]);
            
        });
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
