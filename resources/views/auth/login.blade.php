<h1>Login</h1>

<form method="POST" action="{{ route('login.store') }}">
@csrf
    <div>
        <label>Email</label>
        <input type="email" name="email">
    </div>
    <div>
        <label>Password</label>
        <input type="password" name="password">
    </div>
    <button type="submit">Login</button>
</form>