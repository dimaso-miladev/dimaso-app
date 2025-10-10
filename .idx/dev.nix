{pkgs}: {
  channel = "stable-24.05";
  packages = [
    pkgs.php82,
    pkgs.composer,
    pkgs.mysql80,
    pkgs.nodejs_20,
    pkgs.php82.extensions.mysql,
    pkgs.php82.extensions.pdo,
    pkgs.php82.extensions.mbstring,
    pkgs.php82.extensions.xml,
    pkgs.php82.extensions.ctype,
    pkgs.php82.extensions.json,
    pkgs.mysql84
  ];
  idx.extensions = [
    "svelte.svelte-vscode",
    "vue.volar"
  ];
  idx.previews = {
    previews = {
      web = {
        command = [
          "npm",
          "run",
          "dev",
          "--",
          "--port",
          "$PORT",
          "--host",
          "0.0.0.0"
        ];
        manager = "web";
      };
    };
  };
}