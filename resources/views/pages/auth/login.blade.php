<x-layouts::auth :title="'Connexion'">
    <div class="flex flex-col gap-6">
        <x-auth-header title="Connexion à votre compte" description="Entrez votre nom d'utilisateur et mot de passe" />

        <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" />

        <form method="POST" action="{{ route('login.store') }}" class="flex flex-col gap-6">
            @csrf

            <!-- Nom d'utilisateur -->
            <flux:input
                name="username"
                label="Nom d'utilisateur"
                :value="old('username')"
                type="text"
                required
                autofocus
                autocomplete="username"
                placeholder="nom.utilisateur"
            />

            <!-- Mot de passe -->
            <div class="relative">
                <flux:input
                    name="password"
                    label="Mot de passe"
                    type="password"
                    required
                    autocomplete="current-password"
                    placeholder="••••••••"
                    viewable
                />
            </div>

            <!-- Se souvenir de moi -->
            <flux:checkbox name="remember" label="Se souvenir de moi" :checked="old('remember')" />

            <div class="flex items-center justify-end">
                <flux:button variant="primary" type="submit" class="w-full" data-test="login-button">
                    Se connecter
                </flux:button>
            </div>
        </form>
    </div>
</x-layouts::auth>
