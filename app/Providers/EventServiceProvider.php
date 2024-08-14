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

            $items = Adoption::all()->map(function (Adoption $notificacion) {
                return [
                    'text' => Helper::getAdoptionStatus()[$notificacion['status']],
                    'url' => "adoption-notification/".$notificacion['id'],
                    'icon' => Helper::getAdoptionIcon()[$notificacion['status']].' mr-2',
                    'label' => $notificacion['created_at']->diffForHumans(),
                    'label_color' => (Carbon::parse($notificacion['created_at'])->isToday() ? 'success' : 'light text-muted') . ' float-right',
                    'created_at' => $notificacion['created_at'],
                ];
            })->sortBy(['created_at','desc'])->skip(0)->take(15);

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

            $event->menu->addIn('navbar-notifications', ...$items);

            $event->menu->addIn('navbar-notifications', [
                'text' => 'View All',
                'url' => 'adoption-notification',
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
