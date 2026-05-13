<aside class="modern-side-card">
    <h4 class="section-eyebrow mb-3">Body Scan Features</h4>

    <ul class="modern-feature-list">
        <li class="modern-feature-item active">Biomagnetism Pair Body Scan</li>
        <li class="modern-feature-item">Guided body scan - simplified path to pairs</li>
        <li class="modern-feature-item">Learn at your own speed</li>
        <li class="modern-feature-item">Male and Female models</li>
        <li class="modern-feature-item">Pairs, radical and origin</li>
        <li class="modern-feature-item">Search data &amp; points on the body</li>
        <li class="modern-feature-item">+ direct pairs to patient database</li>
        <li class="modern-feature-item">Free Relaxing Music</li>
    </ul>

    <div class="modern-cta-block text-center">
        <a href="{{ empty(Auth::user()) ? '/register' : '/bodyscan' }}" class="modern-btn modern-btn--primary w-100">
            Get Started / Comenzar
        </a>
    </div>
</aside>
