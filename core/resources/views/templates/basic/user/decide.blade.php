

<div class="desktop" >
    @include($activeTemplate.'user.dashboard')
</div>

<div class="mobile" style="display: none">
    @include($activeTemplate.'user.new_dashboard')
</div>

<style>
    @media (max-width: 780px) {
        .mobile{
            display: block !important;
        }
        .desktop{
            display: none !important;
        }
    }
</style>


