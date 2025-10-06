<!-- bottom nav start -->
<section
    class="position-fixed bottom-0 start-0 end-0 bottom-nav d-flex align-items-center"
>
    <nav class="d-flex align-items-center justify-content-between w-100 px-4 py-2">

        <!-- Home -->
        <div class="d-flex align-items-center flex-column">
            <a href="{{ route('user-index') }}"
               class="{{ request()->routeIs('user-index') ? 'nav-icon-active' : 'nav-icon-inactive' }}">
                <span>
                    <iconify-icon
                        icon="lucide-lab:home"
                        width="2em"
                        height="2em"
                    ></iconify-icon>
                </span>
            </a>
            <span>
                <small class="{{ request()->routeIs('user-index') ? 'nav-icon-active' : 'nav-icon-inactive' }}">
                    Home
                </small>
            </span>
        </div>

        <!-- Set Off -->
        <div class="d-flex align-items-center flex-column">
            <a href="{{ route('user-setoff') }}"
               class="{{ request()->routeIs('user-setoff') ? 'nav-icon-active' : 'nav-icon-inactive' }}">
              <span>
                <iconify-icon
                    icon="material-symbols:display-settings-outline-rounded"
                    width="2em"
                    height="2em"
                ></iconify-icon>
              </span>
            </a>
            <span>
                <small class="{{ request()->routeIs('user-setoff') ? 'nav-icon-active' : 'nav-icon-inactive' }}">
                    Set Off
                </small>
            </span>
        </div>

        <!-- Event -->
        <div class="d-flex align-items-center flex-column">
            <a href="{{ route('user-event') }}"
               class="{{ request()->routeIs('user-event') ? 'nav-icon-active' : 'nav-icon-inactive' }}">
              <span>
                <iconify-icon
                    icon="mynaui:gift"
                    width="2em"
                    height="2em"
                ></iconify-icon>
              </span>
            </a>
            <span>
                <small class="{{ request()->routeIs('user-event') ? 'nav-icon-active' : 'nav-icon-inactive' }}">
                    Event
                </small>
            </span>
        </div>

        <!-- Credit -->
        <div class="d-flex align-items-center flex-column">
            <a href="{{ route('user-credit-score') }}"
               class="{{ request()->routeIs('user-credit-score') ? 'nav-icon-active' : 'nav-icon-inactive' }}">
              <span>
                <iconify-icon
                    icon="streamline-flex:credit-card-approved"
                    width="2em"
                    height="2em"
                ></iconify-icon>
              </span>
            </a>
            <span>
                <small class="{{ request()->routeIs('user-credit-score') ? 'nav-icon-active' : 'nav-icon-inactive' }}">
                    Credit
                </small>
            </span>
        </div>
    </nav>
</section>
<!-- bottom nav end -->
