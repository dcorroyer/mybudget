<?php

declare(strict_types=1);

namespace Castor\Utils\Docker;

use Castor\Context;
use Symfony\Component\Process\Process;

use function Castor\context;
use function Castor\run;

class Docker
{
    private array $commands = [];

    public function __construct(
        private readonly Context $castorContext,
    ) {
    }

    public function add(string $command): self
    {
        $this->commands[] = $command;

        return $this;
    }

    public function addIf(mixed $condition, ?string $key = null, string|array|int|bool|null $value = null): void
    {
        if ($condition !== false) {
            if ($key === null) {
                $this->commands[] = \is_array($value) ? implode(' ', $value) : $value;
            } elseif ($value === null) {
                $this->commands[] = $key;
            } elseif (\is_array($value)) {
                $this->commands[] = $key . ' ' . implode(' ' . $key . ' ', $value);
            } else {
                $this->commands[] = $key . ' ' . $value;
            }
        }
    }

    public function runCommand(): Process
    {
        $commands = $this->mergeCommands('docker', $this->commands);
        $runProcess = run($commands, context: $this->castorContext);
        $this->commands = [];

        return $runProcess;
    }

    public function mergeCommands(mixed ...$commands): string
    {
        $commandsAsArrays = array_map(
            callback: static fn ($command) => \is_array($command) ? $command : explode(' ', $command),
            array: $commands
        );
        $flattened = array_reduce(
            array: $commandsAsArrays,
            callback: static fn ($carry, $item) => [...$carry, ...$item],
            initial: []
        );

        return implode(' ', array_filter($flattened));
    }

    /**
     * Usage:  docker run [OPTIONS] IMAGE [COMMAND] [ARG...].
     */
    public function run(
        string $image,
        array $args = [],
        /* Add a custom host-to-IP mapping (host:ip) */
        false|array $addHost = false,
        /* Add an annotation to the container (passed through to the OCI runtime) (default map[]) */
        false|array $annotation = false,
        /* Attach to STDIN, STDOUT or STDERR */
        false|array $attach = false,
        /* Block IO (relative weight), between 10 and 1000, or 0 to disable (default 0) */
        false|int $blkioWeight = false,
        /* Block IO weight (relative device weight) (default []) */
        false|array $blkioWeightDevice = false,
        /* Add Linux capabilities */
        false|array $capAdd = false,
        /* Drop Linux capabilities */
        false|array $capDrop = false,
        /* Optional parent cgroup for the container */
        false|string $cgroupParent = false,
        /* Cgroup namespace to use (host|private) 'host': Run the container in the Docker host's cgroup namespace 'private': Run the container in its own private cgroup namespace '': Use the cgroup namespace as configured by the default-cgroupns-mode option on the daemon (default) */
        false|string $cgroupns = false,
        /* Write the container ID to the file */
        false|string $cidfile = false,
        /* Limit CPU CFS (Completely Fair Scheduler) period */
        false|int $cpuPeriod = false,
        /* Limit CPU CFS (Completely Fair Scheduler) quota */
        false|int $cpuQuota = false,
        /* Limit CPU real-time period in microseconds */
        false|int $cpuRtPeriod = false,
        /* Limit CPU real-time runtime in microseconds */
        false|int $cpuRtRuntime = false,
        /* CPU shares (relative weight) */
        false|int $cpuShares = false,
        /* Number of CPUs */
        false|int $cpus = false,
        /* CPUs in which to allow execution (0-3, 0,1) */
        false|string $cpusetCpus = false,
        /* MEMs in which to allow execution (0-3, 0,1) */
        false|string $cpusetMems = false,
        /* Run container in background and print container ID */
        bool $detach = false,
        /* Override the key sequence for detaching a container */
        false|string $detachKeys = false,
        /* Add a host device to the container */
        false|array $device = false,
        /* Add a rule to the cgroup allowed devices list */
        false|array $deviceCgroupRule = false,
        /* Limit read rate (bytes per second) from a device (default []) */
        false|array $deviceReadBps = false,
        /* Limit read rate (IO per second) from a device (default []) */
        false|array $deviceReadIops = false,
        /* Limit write rate (bytes per second) to a device (default []) */
        false|array $deviceWriteBps = false,
        /* Limit write rate (IO per second) to a device (default []) */
        false|array $deviceWriteIops = false,
        /* Skip image verification (default true) */
        bool $disableContentTrust = false,
        /* Set custom DNS servers */
        false|array $dns = false,
        /* Set DNS options */
        false|array $dnsOption = false,
        /* Set custom DNS search domains */
        false|array $dnsSearch = false,
        /* Container NIS domain name */
        false|string $domainname = false,
        /* Overwrite the default ENTRYPOINT of the image */
        false|string $entrypoint = false,
        /* Set environment variables */
        false|array $env = false,
        /* Read in a file of environment variables */
        false|array $envFile = false,
        /* Expose a port or a range of ports */
        false|array $expose = false,
        /* gpu-request GPU devices to add to the container ('all' to pass all GPUs) */
        bool $gpus = false,
        /* Add additional groups to join */
        false|array $groupAdd = false,
        /* Command to run to check health */
        false|string $healthCmd = false,
        /* Time between running the check (ms|s|m|h) (default 0s) */
        false|string $healthInterval = false,
        /* Consecutive failures needed to report unhealthy */
        false|int $healthRetries = false,
        /* Time between running the check during the start period (ms|s|m|h) (default 0s) */
        false|string $healthStartInterval = false,
        /* Start period for the container to initialize before starting health-retries countdown (ms|s|m|h) (default 0s) */
        false|string $healthStartPeriod = false,
        /* Maximum time to allow one check to run (ms|s|m|h) (default 0s) */
        false|string $healthTimeout = false,
        /* Print usage */
        bool $help = false,
        /* Container host name */
        false|string $hostname = false,
        /* Run an init inside the container that forwards signals and reaps processes */
        bool $init = false,
        /* Keep STDIN open even if not attached */
        bool $interactive = false,
        /* IPv4 address (e.g., 172.30.100.104) */
        false|string $ip = false,
        /* IPv6 address (e.g., 2001:db8::33) */
        false|string $ip6 = false,
        /* IPC mode to use */
        false|string $ipc = false,
        /* Container isolation technology */
        false|string $isolation = false,
        /* Kernel memory limit */
        false|int $kernelMemory = false,
        /* Set meta data on a container */
        false|array $label = false,
        /* Read in a line delimited file of labels */
        false|array $labelFile = false,
        /* Add link to another container */
        false|array $link = false,
        /* Container IPv4/IPv6 link-local addresses */
        false|array $linkLocalIp = false,
        /* Logging driver for the container */
        false|string $logDriver = false,
        /* Log driver options */
        false|array $logOpt = false,
        /* Container MAC address (e.g., 92:d0:c6:0a:29:33) */
        false|string $macAddress = false,
        /* Memory limit */
        false|int $memory = false,
        /* Memory soft limit */
        false|int $memoryReservation = false,
        /* Swap limit equal to memory plus swap: '-1' to enable unlimited swap */
        false|int $memorySwap = false,
        /* Tune container memory swappiness (0 to 100) (default -1) */
        false|int $memorySwappiness = false,
        /* mount Attach a filesystem mount to the container */
        bool $mount = false,
        /* Assign a name to the container */
        false|string $name = false,
        /* network Connect a container to a network */
        bool $network = false,
        /* Add network-scoped alias for the container */
        false|array $networkAlias = false,
        /* Disable any container-specified HEALTHCHECK */
        bool $noHealthcheck = false,
        /* Disable OOM Killer */
        bool $oomKillDisable = false,
        /* Tune host's OOM preferences (-1000 to 1000) */
        false|int $oomScoreAdj = false,
        /* PID namespace to use */
        false|string $pid = false,
        /* Tune container pids limit (set -1 for unlimited) */
        false|int $pidsLimit = false,
        /* Set platform if server is multi-platform capable */
        false|string $platform = false,
        /* Give extended privileges to this container */
        bool $privileged = false,
        /* Publish a container's port(s) to the host -P, --publish-all Publish all exposed ports to random ports */
        false|array $publish = false,
        /* Pull image before running ("always", "missing", "never") (default "missing") */
        false|string $pull = false,
        /* Suppress the pull output */
        bool $quiet = false,
        /* Mount the container's root filesystem as read only */
        bool $readOnly = false,
        /* Restart policy to apply when a container exits (default "no") */
        false|string $restart = false,
        /* Automatically remove the container when it exits */
        bool $rm = false,
        /* Runtime to use for this container */
        false|string $runtime = false,
        /* Security Options */
        false|array $securityOpt = false,
        /* Size of /dev/shm */
        false|int $shmSize = false,
        /* Proxy received signals to the process (default true) */
        bool $sigProxy = false,
        /* Signal to stop the container */
        false|string $stopSignal = false,
        /* Timeout (in seconds) to stop a container */
        false|int $stopTimeout = false,
        /* Storage driver options for the container */
        false|array $storageOpt = false,
        /* Sysctl options (default map[]) */
        false|array $sysctl = false,
        /* Mount a tmpfs directory */
        false|array $tmpfs = false,
        /* Allocate a pseudo-TTY */
        bool $tty = false,
        /* Ulimit options (default []) */
        false|int $ulimit = false,
        /* Username or UID (format: <name|uid>[:<group|gid>]) */
        false|string $user = false,
        /* UserEntity namespace to use */
        false|string $userns = false,
        /* UTS namespace to use */
        false|string $uts = false,
        /* Bind mount a volume */
        false|array $volume = false,
        /* Optional volume driver for the container */
        false|string $volumeDriver = false,
        /* Mount volumes from the specified container(s) */
        false|array $volumesFrom = false,
        /* Working directory inside the container */
        false|string $workdir = false,
    ): Process {
        $this->add('run');
        $this->addIf($addHost, '--add-host', $addHost);
        $this->addIf($annotation, '--annotation', $annotation);
        $this->addIf($attach, '--attach', $attach);
        $this->addIf($blkioWeight, '--blkio-weight', $blkioWeight);
        $this->addIf($blkioWeightDevice, '--blkio-weight-device', $blkioWeightDevice);
        $this->addIf($capAdd, '--cap-add', $capAdd);
        $this->addIf($capDrop, '--cap-drop', $capDrop);
        $this->addIf($cgroupParent, '--cgroup-parent', $cgroupParent);
        $this->addIf($cgroupns, '--cgroupns', $cgroupns);
        $this->addIf($cidfile, '--cidfile', $cidfile);
        $this->addIf($cpuPeriod, '--cpu-period', $cpuPeriod);
        $this->addIf($cpuQuota, '--cpu-quota', $cpuQuota);
        $this->addIf($cpuRtPeriod, '--cpu-rt-period', $cpuRtPeriod);
        $this->addIf($cpuRtRuntime, '--cpu-rt-runtime', $cpuRtRuntime);
        $this->addIf($cpuShares, '--cpu-shares', $cpuShares);
        $this->addIf($cpus, '--cpus', $cpus);
        $this->addIf($cpusetCpus, '--cpuset-cpus', $cpusetCpus);
        $this->addIf($cpusetMems, '--cpuset-mems', $cpusetMems);
        $this->addIf($detach, '--detach');
        $this->addIf($detachKeys, '--detach-keys', $detachKeys);
        $this->addIf($device, '--device', $device);
        $this->addIf($deviceCgroupRule, '--device-cgroup-rule', $deviceCgroupRule);
        $this->addIf($deviceReadBps, '--device-read-bps', $deviceReadBps);
        $this->addIf($deviceReadIops, '--device-read-iops', $deviceReadIops);
        $this->addIf($deviceWriteBps, '--device-write-bps', $deviceWriteBps);
        $this->addIf($deviceWriteIops, '--device-write-iops', $deviceWriteIops);
        $this->addIf($disableContentTrust, '--disable-content-trust');
        $this->addIf($dns, '--dns', $dns);
        $this->addIf($dnsOption, '--dns-option', $dnsOption);
        $this->addIf($dnsSearch, '--dns-search', $dnsSearch);
        $this->addIf($domainname, '--domainname', $domainname);
        $this->addIf($entrypoint, '--entrypoint', $entrypoint);
        $this->addIf($env, '--env', $env);
        $this->addIf($envFile, '--env-file', $envFile);
        $this->addIf($expose, '--expose', $expose);
        $this->addIf($gpus, '--gpus');
        $this->addIf($groupAdd, '--group-add', $groupAdd);
        $this->addIf($healthCmd, '--health-cmd', $healthCmd);
        $this->addIf($healthInterval, '--health-interval', $healthInterval);
        $this->addIf($healthRetries, '--health-retries', $healthRetries);
        $this->addIf($healthStartInterval, '--health-start-interval', $healthStartInterval);
        $this->addIf($healthStartPeriod, '--health-start-period', $healthStartPeriod);
        $this->addIf($healthTimeout, '--health-timeout', $healthTimeout);
        $this->addIf($help, '--help');
        $this->addIf($hostname, '--hostname', $hostname);
        $this->addIf($init, '--init');
        $this->addIf($interactive, '--interactive');
        $this->addIf($ip, '--ip', $ip);
        $this->addIf($ip6, '--ip6', $ip6);
        $this->addIf($ipc, '--ipc', $ipc);
        $this->addIf($isolation, '--isolation', $isolation);
        $this->addIf($kernelMemory, '--kernel-memory', $kernelMemory);
        $this->addIf($label, '--label', $label);
        $this->addIf($labelFile, '--label-file', $labelFile);
        $this->addIf($link, '--link', $link);
        $this->addIf($linkLocalIp, '--link-local-ip', $linkLocalIp);
        $this->addIf($logDriver, '--log-driver', $logDriver);
        $this->addIf($logOpt, '--log-opt', $logOpt);
        $this->addIf($macAddress, '--mac-address', $macAddress);
        $this->addIf($memory, '--memory', $memory);
        $this->addIf($memoryReservation, '--memory-reservation', $memoryReservation);
        $this->addIf($memorySwap, '--memory-swap', $memorySwap);
        $this->addIf($memorySwappiness, '--memory-swappiness', $memorySwappiness);
        $this->addIf($mount, '--mount');
        $this->addIf($name, '--name', $name);
        $this->addIf($network, '--network');
        $this->addIf($networkAlias, '--network-alias', $networkAlias);
        $this->addIf($noHealthcheck, '--no-healthcheck');
        $this->addIf($oomKillDisable, '--oom-kill-disable');
        $this->addIf($oomScoreAdj, '--oom-score-adj', $oomScoreAdj);
        $this->addIf($pid, '--pid', $pid);
        $this->addIf($pidsLimit, '--pids-limit', $pidsLimit);
        $this->addIf($platform, '--platform', $platform);
        $this->addIf($privileged, '--privileged');
        $this->addIf($publish, '--publish', $publish);
        $this->addIf($pull, '--pull', $pull);
        $this->addIf($quiet, '--quiet');
        $this->addIf($readOnly, '--read-only');
        $this->addIf($restart, '--restart', $restart);
        $this->addIf($rm, '--rm');
        $this->addIf($runtime, '--runtime', $runtime);
        $this->addIf($securityOpt, '--security-opt', $securityOpt);
        $this->addIf($shmSize, '--shm-size', $shmSize);
        $this->addIf($sigProxy, '--sig-proxy');
        $this->addIf($stopSignal, '--stop-signal', $stopSignal);
        $this->addIf($stopTimeout, '--stop-timeout', $stopTimeout);
        $this->addIf($storageOpt, '--storage-opt', $storageOpt);
        $this->addIf($sysctl, '--sysctl', $sysctl);
        $this->addIf($tmpfs, '--tmpfs', $tmpfs);
        $this->addIf($tty, '--tty');
        $this->addIf($ulimit, '--ulimit', $ulimit);
        $this->addIf($user, '--user', $user);
        $this->addIf($userns, '--userns', $userns);
        $this->addIf($uts, '--uts', $uts);
        $this->addIf($volume, '--volume', $volume);
        $this->addIf($volumeDriver, '--volume-driver', $volumeDriver);
        $this->addIf($volumesFrom, '--volumes-from', $volumesFrom);
        $this->addIf($workdir, '--workdir', $workdir);
        $this->addIf($image, null, $image);
        $this->addIf($args, null, $args);

        return $this->runCommand();
    }

    /**
     * Usage:  docker exec [OPTIONS] CONTAINER COMMAND [ARG...].
     */
    public function exec(
        string $container,
        array $args = [],
        /* Detached mode: run command in the background */
        bool $detach = false,
        /* Override the key sequence for detaching a container */
        false|string $detachKeys = false,
        /* Set environment variables */
        false|array $env = false,
        /* Read in a file of environment variables */
        false|array $envFile = false,
        /* Keep STDIN open even if not attached */
        bool $interactive = false,
        /* Give extended privileges to the command */
        bool $privileged = false,
        /* Allocate a pseudo-TTY */
        bool $tty = false,
        /* Username or UID (format: "<name|uid>[:<group|gid>]") */
        false|string $user = false,
        /* Working directory inside the container */
        false|string $workdir = false,
    ): Process {
        $this->add('exec');
        $this->addIf($detach, '--detach');
        $this->addIf($detachKeys, '--detach-keys', $detachKeys);
        $this->addIf($env, '--env', $env);
        $this->addIf($envFile, '--env-file', $envFile);
        $this->addIf($interactive, '--interactive');
        $this->addIf($privileged, '--privileged');
        $this->addIf($tty, '--tty');
        $this->addIf($user, '--user', $user);
        $this->addIf($workdir, '--workdir', $workdir);
        $this->addIf($container, null, $container);
        $this->addIf($args, null, $args);

        return $this->runCommand();
    }

    /**
     * Usage:  docker ps [OPTIONS].
     */
    public function ps(
        /* Show all containers (default shows just running) */
        bool $all = false,
        /* Filter output based on conditions provided */
        false|array $filter = false,
        /* Format output using a custom template: 'table': Print output in table format with column headers (default) 'table TEMPLATE': Print output in table format using the given Go template 'json': Print in JSON format 'TEMPLATE': Print output using the given Go template. Refer to https://docs.docker.com/go/formatting/ for more information about formatting output with templates */
        false|string $format = false,
        /* Show n last created containers (includes all states) (default -1) */
        false|int $last = false,
        /* Show the latest created container (includes all states) */
        bool $latest = false,
        /* Don't truncate output */
        bool $noTrunc = false,
        /* Only display container IDs */
        bool $quiet = false,
        /* Display total file sizes */
        bool $size = false,
    ): Process {
        $this->add('ps');
        $this->addIf($all, '--all');
        $this->addIf($filter, '--filter', $filter);
        $this->addIf($format, '--format', $format);
        $this->addIf($last, '--last', $last);
        $this->addIf($latest, '--latest');
        $this->addIf($noTrunc, '--no-trunc');
        $this->addIf($quiet, '--quiet');
        $this->addIf($size, '--size');

        return $this->runCommand();
    }

    /**
     * Usage:  docker buildx build [OPTIONS] PATH | URL | -.
     */
    public function build(
        string $path,
        string $url,
        /* strings Add a custom host-to-IP mapping (format: "host:ip") */
        bool $addHost = false,
        /* strings Allow extra privileged entitlement (e.g., "network.host", "security.insecure") */
        bool $allow = false,
        /* Add annotation to the image */
        false|array $annotation = false,
        /* Attestation parameters (format: "type=sbom,generator=image") */
        false|array $attest = false,
        /* Set build-time variables */
        false|array $buildArg = false,
        /* Additional build contexts (e.g., name=path) */
        false|array $buildContext = false,
        /* Override the configured builder instance (default "default") */
        false|string $builder = false,
        /* External cache sources (e.g., "user/app:cache", "type=local,src=path/to/dir") */
        false|array $cacheFrom = false,
        /* Cache export destinations (e.g., "user/app:cache", "type=local,dest=path/to/dir") */
        false|array $cacheTo = false,
        /* Set the parent cgroup for the "RUN" instructions during build */
        false|string $cgroupParent = false,
        /* Name of the Dockerfile (default: "PATH/Dockerfile") */
        false|string $file = false,
        /* Write the image ID to the file */
        false|string $iidfile = false,
        /* Set metadata for an image */
        false|array $label = false,
        /* Shorthand for "--output=type=docker" */
        bool $load = false,
        /* Write build result metadata to the file */
        false|string $metadataFile = false,
        /* Set the networking mode for the "RUN" instructions during build (default "default") */
        false|string $network = false,
        /* Do not use cache when building the image */
        bool $noCache = false,
        /* Do not cache specified stages */
        false|array $noCacheFilter = false,
        /* Output destination (format: "type=local,dest=path") */
        false|array $output = false,
        /* Set target platform for build */
        false|array $platform = false,
        /* Set type of progress output ("auto", "plain", "tty"). Use plain to show container output (default "auto") */
        false|string $progress = false,
        /* Shorthand for "--attest=type=provenance" */
        false|string $provenance = false,
        /* Always attempt to pull all referenced images */
        bool $pull = false,
        /* Shorthand for "--output=type=registry" */
        bool $push = false,
        /* Suppress the build output and print image ID on success */
        bool $quiet = false,
        /* Shorthand for "--attest=type=sbom" */
        false|string $sbom = false,
        /* Secret to expose to the build (format: "id=mysecret[,src=/local/secret]") */
        false|array $secret = false,
        /* Size of "/dev/shm" */
        false|int $shmSize = false,
        /* SSH agent socket or keys to expose to the build (format: "default|<id>[=<socket>|<key>[,<key>]]") */
        false|array $ssh = false,
        /* Name and optionally a tag (format: "name:tag") */
        false|array $tag = false,
        /* Set the target build stage to build */
        false|string $target = false,
        /* Ulimit options (default []) */
        false|int $ulimit = false,
    ): Process {
        $this->add('build');
        $this->addIf($addHost, '--add-host');
        $this->addIf($allow, '--allow');
        $this->addIf($annotation, '--annotation', $annotation);
        $this->addIf($attest, '--attest', $attest);
        $this->addIf($buildArg, '--build-arg', $buildArg);
        $this->addIf($buildContext, '--build-context', $buildContext);
        $this->addIf($builder, '--builder', $builder);
        $this->addIf($cacheFrom, '--cache-from', $cacheFrom);
        $this->addIf($cacheTo, '--cache-to', $cacheTo);
        $this->addIf($cgroupParent, '--cgroup-parent', $cgroupParent);
        $this->addIf($file, '--file', $file);
        $this->addIf($iidfile, '--iidfile', $iidfile);
        $this->addIf($label, '--label', $label);
        $this->addIf($load, '--load');
        $this->addIf($metadataFile, '--metadata-file', $metadataFile);
        $this->addIf($network, '--network', $network);
        $this->addIf($noCache, '--no-cache');
        $this->addIf($noCacheFilter, '--no-cache-filter', $noCacheFilter);
        $this->addIf($output, '--output', $output);
        $this->addIf($platform, '--platform', $platform);
        $this->addIf($progress, '--progress', $progress);
        $this->addIf($provenance, '--provenance', $provenance);
        $this->addIf($pull, '--pull');
        $this->addIf($push, '--push');
        $this->addIf($quiet, '--quiet');
        $this->addIf($sbom, '--sbom', $sbom);
        $this->addIf($secret, '--secret', $secret);
        $this->addIf($shmSize, '--shm-size', $shmSize);
        $this->addIf($ssh, '--ssh', $ssh);
        $this->addIf($tag, '--tag', $tag);
        $this->addIf($target, '--target', $target);
        $this->addIf($ulimit, '--ulimit', $ulimit);
        $this->addIf($path, null, $path);
        $this->addIf($url, null, $url);

        return $this->runCommand();
    }

    /**
     * Usage:  docker pull [OPTIONS] NAME[:TAG|@DIGEST].
     */
    public function pull(
        string $name,
        string $tag,
        string $digest,
        /* Download all tagged images in the repository */
        bool $allTags = false,
        /* Skip image verification (default true) */
        bool $disableContentTrust = false,
        /* Set platform if server is multi-platform capable */
        false|string $platform = false,
        /* Suppress verbose output */
        bool $quiet = false,
    ): Process {
        $this->add('pull');
        $this->addIf($allTags, '--all-tags');
        $this->addIf($disableContentTrust, '--disable-content-trust');
        $this->addIf($platform, '--platform', $platform);
        $this->addIf($quiet, '--quiet');
        $this->addIf($name, null, $name);
        $this->addIf($tag, null, $tag);
        $this->addIf($digest, null, $digest);

        return $this->runCommand();
    }

    /**
     * Usage:  docker push [OPTIONS] NAME[:TAG].
     */
    public function push(
        string $name,
        string $tag,
        /* Push all tags of an image to the repository */
        bool $allTags = false,
        /* Skip image signing (default true) */
        bool $disableContentTrust = false,
        /* Suppress verbose output */
        bool $quiet = false,
    ): Process {
        $this->add('push');
        $this->addIf($allTags, '--all-tags');
        $this->addIf($disableContentTrust, '--disable-content-trust');
        $this->addIf($quiet, '--quiet');
        $this->addIf($name, null, $name);
        $this->addIf($tag, null, $tag);

        return $this->runCommand();
    }

    /**
     * Usage:  docker images [OPTIONS] [REPOSITORY[:TAG]].
     */
    public function images(
        string $repository,
        string $tag,
        /* Show all images (default hides intermediate images) */
        bool $all = false,
        /* Show digests */
        bool $digests = false,
        /* Filter output based on conditions provided */
        false|array $filter = false,
        /* Format output using a custom template: 'table': Print output in table format with column headers (default) 'table TEMPLATE': Print output in table format using the given Go template 'json': Print in JSON format 'TEMPLATE': Print output using the given Go template. Refer to https://docs.docker.com/go/formatting/ for more information about formatting output with templates */
        false|string $format = false,
        /* Don't truncate output */
        bool $noTrunc = false,
        /* Only show image IDs */
        bool $quiet = false,
    ): Process {
        $this->add('images');
        $this->addIf($all, '--all');
        $this->addIf($digests, '--digests');
        $this->addIf($filter, '--filter', $filter);
        $this->addIf($format, '--format', $format);
        $this->addIf($noTrunc, '--no-trunc');
        $this->addIf($quiet, '--quiet');
        $this->addIf($repository, null, $repository);
        $this->addIf($tag, null, $tag);

        return $this->runCommand();
    }

    /**
     * Usage:  docker login [OPTIONS] [SERVER].
     */
    public function login(
        string $server,
        /* Password */
        false|string $password = false,
        /* Take the password from stdin */
        bool $passwordStdin = false,
        /* Username */
        false|string $username = false,
    ): Process {
        $this->add('login');
        $this->addIf($password, '--password', $password);
        $this->addIf($passwordStdin, '--password-stdin');
        $this->addIf($username, '--username', $username);
        $this->addIf($server, null, $server);

        return $this->runCommand();
    }

    /**
     * Usage:  docker logout [SERVER].
     */
    public function logout(string $server): Process
    {
        $this->add('logout');

        $this->addIf($server, null, $server);

        return $this->runCommand();
    }

    /**
     * Usage:  docker search [OPTIONS] TERM.
     */
    public function search(
        string $term,
        /* Filter output based on conditions provided */
        false|array $filter = false,
        /* Pretty-print search using a Go template */
        false|string $format = false,
        /* Max number of search results */
        false|int $limit = false,
        /* Don't truncate output */
        bool $noTrunc = false,
    ): Process {
        $this->add('search');
        $this->addIf($filter, '--filter', $filter);
        $this->addIf($format, '--format', $format);
        $this->addIf($limit, '--limit', $limit);
        $this->addIf($noTrunc, '--no-trunc');
        $this->addIf($term, null, $term);

        return $this->runCommand();
    }

    /**
     * Usage:  docker version [OPTIONS].
     */
    public function version(
        /* Format output using a custom template: 'json': Print in JSON format 'TEMPLATE': Print output using the given Go template. Refer to https://docs.docker.com/go/formatting/ for more information about formatting output with templates */
        false|string $format = false,
    ): Process {
        $this->add('version');
        $this->addIf($format, '--format', $format);

        return $this->runCommand();
    }

    /**
     * Usage:  docker info [OPTIONS].
     */
    public function info(
        /* Format output using a custom template: 'json': Print in JSON format 'TEMPLATE': Print output using the given Go template. Refer to https://docs.docker.com/go/formatting/ for more information about formatting output with templates */
        false|string $format = false,
    ): Process {
        $this->add('info');
        $this->addIf($format, '--format', $format);

        return $this->runCommand();
    }

    public function builder(
        /* Override the configured builder instance (default "default") */
        false|string $builder = false,
    ): DockerBuilder {
        return new DockerBuilder(docker: $this, builder: $builder);
    }

    public function buildx(
        /* Override the configured builder instance */
        false|string $builder = false,
    ): DockerBuildx {
        return new DockerBuildx(docker: $this, builder: $builder);
    }

    public function compose(
        /* Control when to print ANSI control characters ("never"|"always"|"auto") (default "auto") */
        false|string $ansi = false,
        /* Run compose in backward compatibility mode */
        bool $compatibility = false,
        /* Execute command in dry run mode */
        bool $dryRun = false,
        /* Specify an alternate environment file. */
        false|array $envFile = false,
        /* Compose configuration files */
        false|array $file = false,
        /* Control max parallelism, -1 for unlimited (default -1) */
        false|int $parallel = false,
        /* Specify a profile to enable */
        false|array $profile = false,
        /* Set type of progress output (auto, tty, plain, quiet) (default "auto") */
        false|string $progress = false,
        /* Specify an alternate working directory (default: the path of the, first specified, Compose file) */
        false|string $projectDirectory = false,
        /* Project name */
        false|string $projectName = false,
    ): DockerCompose {
        return new DockerCompose(
            docker: $this,
            ansi: $ansi,
            compatibility: $compatibility,
            dryRun: $dryRun,
            envFile: $envFile,
            file: $file,
            parallel: $parallel,
            profile: $profile,
            progress: $progress,
            projectDirectory: $projectDirectory,
            projectName: $projectName
        );
    }

    public function container(): DockerContainer
    {
        return new DockerContainer(docker: $this);
    }

    public function context(): DockerContext
    {
        return new DockerContext(docker: $this);
    }

    public function image(): DockerImage
    {
        return new DockerImage(docker: $this);
    }

    public function manifest(): DockerManifest
    {
        return new DockerManifest(docker: $this);
    }

    public function network(): DockerNetwork
    {
        return new DockerNetwork(docker: $this);
    }

    public function plugin(): DockerPlugin
    {
        return new DockerPlugin(docker: $this);
    }

    public function system(): DockerSystem
    {
        return new DockerSystem(docker: $this);
    }

    public function trust(): DockerTrust
    {
        return new DockerTrust(docker: $this);
    }

    public function volume(): DockerVolume
    {
        return new DockerVolume(docker: $this);
    }

    public function swarm(): DockerSwarm
    {
        return new DockerSwarm(docker: $this);
    }

    /**
     * Usage:  docker attach [OPTIONS] CONTAINER.
     */
    public function attach(
        string $container,
        /* Override the key sequence for detaching a container */
        false|string $detachKeys = false,
        /* Do not attach STDIN */
        bool $noStdin = false,
        /* Proxy all received signals to the process (default true) */
        bool $sigProxy = false,
    ): Process {
        $this->add('attach');
        $this->addIf($detachKeys, '--detach-keys', $detachKeys);
        $this->addIf($noStdin, '--no-stdin');
        $this->addIf($sigProxy, '--sig-proxy');
        $this->addIf($container, null, $container);

        return $this->runCommand();
    }

    /**
     * Usage:  docker commit [OPTIONS] CONTAINER [REPOSITORY[:TAG]].
     */
    public function commit(
        string $container,
        string $repository,
        string $tag,
        /* Author (e.g., "John Hannibal Smith <hannibal@a-team.com>") */
        false|string $author = false,
        /* Apply Dockerfile instruction to the created image */
        false|array $change = false,
        /* Commit message */
        false|string $message = false,
        /* Pause container during commit (default true) */
        bool $pause = false,
    ): Process {
        $this->add('commit');
        $this->addIf($author, '--author', $author);
        $this->addIf($change, '--change', $change);
        $this->addIf($message, '--message', $message);
        $this->addIf($pause, '--pause');
        $this->addIf($container, null, $container);
        $this->addIf($repository, null, $repository);
        $this->addIf($tag, null, $tag);

        return $this->runCommand();
    }

    /**
     * Usage:  docker cp [OPTIONS] CONTAINER:SRC_PATH DEST_PATH|-     docker cp [OPTIONS] SRC_PATH|- CONTAINER:DEST_PATH.
     */
    public function cp(
        string $container,
        string $src,
        string $path,
        string $dest,
        /* Archive mode (copy all uid/gid information) -L, --follow-link Always follow symbol link in SRC_PATH */
        bool $archive = false,
        /* Suppress progress output during copy. Progress output is automatically suppressed if no terminal is attached */
        bool $quiet = false,
    ): Process {
        $this->add('cp');
        $this->addIf($archive, '--archive');
        $this->addIf($quiet, '--quiet');
        $this->addIf($container, null, $container);
        $this->addIf($src, null, $src);
        $this->addIf($path, null, $path);
        $this->addIf($dest, null, $dest);
        $this->addIf($path, null, $path);
        $this->addIf($src, null, $src);
        $this->addIf($path, null, $path);
        $this->addIf($container, null, $container);
        $this->addIf($dest, null, $dest);
        $this->addIf($path, null, $path);

        return $this->runCommand();
    }

    /**
     * Usage:  docker create [OPTIONS] IMAGE [COMMAND] [ARG...].
     */
    public function create(
        string $image,
        array $args = [],
        /* Add a custom host-to-IP mapping (host:ip) */
        false|array $addHost = false,
        /* Add an annotation to the container (passed through to the OCI runtime) (default map[]) */
        false|array $annotation = false,
        /* Attach to STDIN, STDOUT or STDERR */
        false|array $attach = false,
        /* Block IO (relative weight), between 10 and 1000, or 0 to disable (default 0) */
        false|int $blkioWeight = false,
        /* Block IO weight (relative device weight) (default []) */
        false|array $blkioWeightDevice = false,
        /* Add Linux capabilities */
        false|array $capAdd = false,
        /* Drop Linux capabilities */
        false|array $capDrop = false,
        /* Optional parent cgroup for the container */
        false|string $cgroupParent = false,
        /* Cgroup namespace to use (host|private) 'host': Run the container in the Docker host's cgroup namespace 'private': Run the container in its own private cgroup namespace '': Use the cgroup namespace as configured by the default-cgroupns-mode option on the daemon (default) */
        false|string $cgroupns = false,
        /* Write the container ID to the file */
        false|string $cidfile = false,
        /* Limit CPU CFS (Completely Fair Scheduler) period */
        false|int $cpuPeriod = false,
        /* Limit CPU CFS (Completely Fair Scheduler) quota */
        false|int $cpuQuota = false,
        /* Limit CPU real-time period in microseconds */
        false|int $cpuRtPeriod = false,
        /* Limit CPU real-time runtime in microseconds */
        false|int $cpuRtRuntime = false,
        /* CPU shares (relative weight) */
        false|int $cpuShares = false,
        /* Number of CPUs */
        false|int $cpus = false,
        /* CPUs in which to allow execution (0-3, 0,1) */
        false|string $cpusetCpus = false,
        /* MEMs in which to allow execution (0-3, 0,1) */
        false|string $cpusetMems = false,
        /* Add a host device to the container */
        false|array $device = false,
        /* Add a rule to the cgroup allowed devices list */
        false|array $deviceCgroupRule = false,
        /* Limit read rate (bytes per second) from a device (default []) */
        false|array $deviceReadBps = false,
        /* Limit read rate (IO per second) from a device (default []) */
        false|array $deviceReadIops = false,
        /* Limit write rate (bytes per second) to a device (default []) */
        false|array $deviceWriteBps = false,
        /* Limit write rate (IO per second) to a device (default []) */
        false|array $deviceWriteIops = false,
        /* Skip image verification (default true) */
        bool $disableContentTrust = false,
        /* Set custom DNS servers */
        false|array $dns = false,
        /* Set DNS options */
        false|array $dnsOption = false,
        /* Set custom DNS search domains */
        false|array $dnsSearch = false,
        /* Container NIS domain name */
        false|string $domainname = false,
        /* Overwrite the default ENTRYPOINT of the image */
        false|string $entrypoint = false,
        /* Set environment variables */
        false|array $env = false,
        /* Read in a file of environment variables */
        false|array $envFile = false,
        /* Expose a port or a range of ports */
        false|array $expose = false,
        /* gpu-request GPU devices to add to the container ('all' to pass all GPUs) */
        bool $gpus = false,
        /* Add additional groups to join */
        false|array $groupAdd = false,
        /* Command to run to check health */
        false|string $healthCmd = false,
        /* Time between running the check (ms|s|m|h) (default 0s) */
        false|string $healthInterval = false,
        /* Consecutive failures needed to report unhealthy */
        false|int $healthRetries = false,
        /* Time between running the check during the start period (ms|s|m|h) (default 0s) */
        false|string $healthStartInterval = false,
        /* Start period for the container to initialize before starting health-retries countdown (ms|s|m|h) (default 0s) */
        false|string $healthStartPeriod = false,
        /* Maximum time to allow one check to run (ms|s|m|h) (default 0s) */
        false|string $healthTimeout = false,
        /* Print usage */
        bool $help = false,
        /* Container host name */
        false|string $hostname = false,
        /* Run an init inside the container that forwards signals and reaps processes */
        bool $init = false,
        /* Keep STDIN open even if not attached */
        bool $interactive = false,
        /* IPv4 address (e.g., 172.30.100.104) */
        false|string $ip = false,
        /* IPv6 address (e.g., 2001:db8::33) */
        false|string $ip6 = false,
        /* IPC mode to use */
        false|string $ipc = false,
        /* Container isolation technology */
        false|string $isolation = false,
        /* Kernel memory limit */
        false|int $kernelMemory = false,
        /* Set meta data on a container */
        false|array $label = false,
        /* Read in a line delimited file of labels */
        false|array $labelFile = false,
        /* Add link to another container */
        false|array $link = false,
        /* Container IPv4/IPv6 link-local addresses */
        false|array $linkLocalIp = false,
        /* Logging driver for the container */
        false|string $logDriver = false,
        /* Log driver options */
        false|array $logOpt = false,
        /* Container MAC address (e.g., 92:d0:c6:0a:29:33) */
        false|string $macAddress = false,
        /* Memory limit */
        false|int $memory = false,
        /* Memory soft limit */
        false|int $memoryReservation = false,
        /* Swap limit equal to memory plus swap: '-1' to enable unlimited swap */
        false|int $memorySwap = false,
        /* Tune container memory swappiness (0 to 100) (default -1) */
        false|int $memorySwappiness = false,
        /* mount Attach a filesystem mount to the container */
        bool $mount = false,
        /* Assign a name to the container */
        false|string $name = false,
        /* network Connect a container to a network */
        bool $network = false,
        /* Add network-scoped alias for the container */
        false|array $networkAlias = false,
        /* Disable any container-specified HEALTHCHECK */
        bool $noHealthcheck = false,
        /* Disable OOM Killer */
        bool $oomKillDisable = false,
        /* Tune host's OOM preferences (-1000 to 1000) */
        false|int $oomScoreAdj = false,
        /* PID namespace to use */
        false|string $pid = false,
        /* Tune container pids limit (set -1 for unlimited) */
        false|int $pidsLimit = false,
        /* Set platform if server is multi-platform capable */
        false|string $platform = false,
        /* Give extended privileges to this container */
        bool $privileged = false,
        /* Publish a container's port(s) to the host -P, --publish-all Publish all exposed ports to random ports */
        false|array $publish = false,
        /* Pull image before creating ("always", "|missing", "never") (default "missing") */
        false|string $pull = false,
        /* Suppress the pull output */
        bool $quiet = false,
        /* Mount the container's root filesystem as read only */
        bool $readOnly = false,
        /* Restart policy to apply when a container exits (default "no") */
        false|string $restart = false,
        /* Automatically remove the container when it exits */
        bool $rm = false,
        /* Runtime to use for this container */
        false|string $runtime = false,
        /* Security Options */
        false|array $securityOpt = false,
        /* Size of /dev/shm */
        false|int $shmSize = false,
        /* Signal to stop the container */
        false|string $stopSignal = false,
        /* Timeout (in seconds) to stop a container */
        false|int $stopTimeout = false,
        /* Storage driver options for the container */
        false|array $storageOpt = false,
        /* Sysctl options (default map[]) */
        false|array $sysctl = false,
        /* Mount a tmpfs directory */
        false|array $tmpfs = false,
        /* Allocate a pseudo-TTY */
        bool $tty = false,
        /* Ulimit options (default []) */
        false|int $ulimit = false,
        /* Username or UID (format: <name|uid>[:<group|gid>]) */
        false|string $user = false,
        /* UserEntity namespace to use */
        false|string $userns = false,
        /* UTS namespace to use */
        false|string $uts = false,
        /* Bind mount a volume */
        false|array $volume = false,
        /* Optional volume driver for the container */
        false|string $volumeDriver = false,
        /* Mount volumes from the specified container(s) */
        false|array $volumesFrom = false,
        /* Working directory inside the container */
        false|string $workdir = false,
    ): Process {
        $this->add('create');
        $this->addIf($addHost, '--add-host', $addHost);
        $this->addIf($annotation, '--annotation', $annotation);
        $this->addIf($attach, '--attach', $attach);
        $this->addIf($blkioWeight, '--blkio-weight', $blkioWeight);
        $this->addIf($blkioWeightDevice, '--blkio-weight-device', $blkioWeightDevice);
        $this->addIf($capAdd, '--cap-add', $capAdd);
        $this->addIf($capDrop, '--cap-drop', $capDrop);
        $this->addIf($cgroupParent, '--cgroup-parent', $cgroupParent);
        $this->addIf($cgroupns, '--cgroupns', $cgroupns);
        $this->addIf($cidfile, '--cidfile', $cidfile);
        $this->addIf($cpuPeriod, '--cpu-period', $cpuPeriod);
        $this->addIf($cpuQuota, '--cpu-quota', $cpuQuota);
        $this->addIf($cpuRtPeriod, '--cpu-rt-period', $cpuRtPeriod);
        $this->addIf($cpuRtRuntime, '--cpu-rt-runtime', $cpuRtRuntime);
        $this->addIf($cpuShares, '--cpu-shares', $cpuShares);
        $this->addIf($cpus, '--cpus', $cpus);
        $this->addIf($cpusetCpus, '--cpuset-cpus', $cpusetCpus);
        $this->addIf($cpusetMems, '--cpuset-mems', $cpusetMems);
        $this->addIf($device, '--device', $device);
        $this->addIf($deviceCgroupRule, '--device-cgroup-rule', $deviceCgroupRule);
        $this->addIf($deviceReadBps, '--device-read-bps', $deviceReadBps);
        $this->addIf($deviceReadIops, '--device-read-iops', $deviceReadIops);
        $this->addIf($deviceWriteBps, '--device-write-bps', $deviceWriteBps);
        $this->addIf($deviceWriteIops, '--device-write-iops', $deviceWriteIops);
        $this->addIf($disableContentTrust, '--disable-content-trust');
        $this->addIf($dns, '--dns', $dns);
        $this->addIf($dnsOption, '--dns-option', $dnsOption);
        $this->addIf($dnsSearch, '--dns-search', $dnsSearch);
        $this->addIf($domainname, '--domainname', $domainname);
        $this->addIf($entrypoint, '--entrypoint', $entrypoint);
        $this->addIf($env, '--env', $env);
        $this->addIf($envFile, '--env-file', $envFile);
        $this->addIf($expose, '--expose', $expose);
        $this->addIf($gpus, '--gpus');
        $this->addIf($groupAdd, '--group-add', $groupAdd);
        $this->addIf($healthCmd, '--health-cmd', $healthCmd);
        $this->addIf($healthInterval, '--health-interval', $healthInterval);
        $this->addIf($healthRetries, '--health-retries', $healthRetries);
        $this->addIf($healthStartInterval, '--health-start-interval', $healthStartInterval);
        $this->addIf($healthStartPeriod, '--health-start-period', $healthStartPeriod);
        $this->addIf($healthTimeout, '--health-timeout', $healthTimeout);
        $this->addIf($help, '--help');
        $this->addIf($hostname, '--hostname', $hostname);
        $this->addIf($init, '--init');
        $this->addIf($interactive, '--interactive');
        $this->addIf($ip, '--ip', $ip);
        $this->addIf($ip6, '--ip6', $ip6);
        $this->addIf($ipc, '--ipc', $ipc);
        $this->addIf($isolation, '--isolation', $isolation);
        $this->addIf($kernelMemory, '--kernel-memory', $kernelMemory);
        $this->addIf($label, '--label', $label);
        $this->addIf($labelFile, '--label-file', $labelFile);
        $this->addIf($link, '--link', $link);
        $this->addIf($linkLocalIp, '--link-local-ip', $linkLocalIp);
        $this->addIf($logDriver, '--log-driver', $logDriver);
        $this->addIf($logOpt, '--log-opt', $logOpt);
        $this->addIf($macAddress, '--mac-address', $macAddress);
        $this->addIf($memory, '--memory', $memory);
        $this->addIf($memoryReservation, '--memory-reservation', $memoryReservation);
        $this->addIf($memorySwap, '--memory-swap', $memorySwap);
        $this->addIf($memorySwappiness, '--memory-swappiness', $memorySwappiness);
        $this->addIf($mount, '--mount');
        $this->addIf($name, '--name', $name);
        $this->addIf($network, '--network');
        $this->addIf($networkAlias, '--network-alias', $networkAlias);
        $this->addIf($noHealthcheck, '--no-healthcheck');
        $this->addIf($oomKillDisable, '--oom-kill-disable');
        $this->addIf($oomScoreAdj, '--oom-score-adj', $oomScoreAdj);
        $this->addIf($pid, '--pid', $pid);
        $this->addIf($pidsLimit, '--pids-limit', $pidsLimit);
        $this->addIf($platform, '--platform', $platform);
        $this->addIf($privileged, '--privileged');
        $this->addIf($publish, '--publish', $publish);
        $this->addIf($pull, '--pull', $pull);
        $this->addIf($quiet, '--quiet');
        $this->addIf($readOnly, '--read-only');
        $this->addIf($restart, '--restart', $restart);
        $this->addIf($rm, '--rm');
        $this->addIf($runtime, '--runtime', $runtime);
        $this->addIf($securityOpt, '--security-opt', $securityOpt);
        $this->addIf($shmSize, '--shm-size', $shmSize);
        $this->addIf($stopSignal, '--stop-signal', $stopSignal);
        $this->addIf($stopTimeout, '--stop-timeout', $stopTimeout);
        $this->addIf($storageOpt, '--storage-opt', $storageOpt);
        $this->addIf($sysctl, '--sysctl', $sysctl);
        $this->addIf($tmpfs, '--tmpfs', $tmpfs);
        $this->addIf($tty, '--tty');
        $this->addIf($ulimit, '--ulimit', $ulimit);
        $this->addIf($user, '--user', $user);
        $this->addIf($userns, '--userns', $userns);
        $this->addIf($uts, '--uts', $uts);
        $this->addIf($volume, '--volume', $volume);
        $this->addIf($volumeDriver, '--volume-driver', $volumeDriver);
        $this->addIf($volumesFrom, '--volumes-from', $volumesFrom);
        $this->addIf($workdir, '--workdir', $workdir);
        $this->addIf($image, null, $image);
        $this->addIf($args, null, $args);

        return $this->runCommand();
    }

    /**
     * Usage:  docker diff CONTAINER.
     */
    public function diff(string $container): Process
    {
        $this->add('diff');

        $this->addIf($container, null, $container);

        return $this->runCommand();
    }

    /**
     * Usage:  docker events [OPTIONS].
     */
    public function events(
        /* Filter output based on conditions provided */
        false|array $filter = false,
        /* Format output using a custom template: 'json': Print in JSON format 'TEMPLATE': Print output using the given Go template. Refer to https://docs.docker.com/go/formatting/ for more information about formatting output with templates */
        false|string $format = false,
        /* Show all events created since timestamp */
        false|string $since = false,
        /* Stream events until this timestamp */
        false|string $until = false,
    ): Process {
        $this->add('events');
        $this->addIf($filter, '--filter', $filter);
        $this->addIf($format, '--format', $format);
        $this->addIf($since, '--since', $since);
        $this->addIf($until, '--until', $until);

        return $this->runCommand();
    }

    /**
     * Usage:  docker export [OPTIONS] CONTAINER.
     */
    public function export(
        string $container,
        /* Write to a file, instead of STDOUT */
        false|string $output = false,
    ): Process {
        $this->add('export');
        $this->addIf($output, '--output', $output);
        $this->addIf($container, null, $container);

        return $this->runCommand();
    }

    /**
     * Usage:  docker history [OPTIONS] IMAGE.
     */
    public function history(
        string $image,
        /* Format output using a custom template: 'table': Print output in table format with column headers (default) 'table TEMPLATE': Print output in table format using the given Go template 'json': Print in JSON format 'TEMPLATE': Print output using the given Go template. Refer to https://docs.docker.com/go/formatting/ for more information about formatting output with templates -H, --human Print sizes and dates in human readable format (default true) */
        false|string $format = false,
        /* Don't truncate output */
        bool $noTrunc = false,
        /* Only show image IDs */
        bool $quiet = false,
    ): Process {
        $this->add('history');
        $this->addIf($format, '--format', $format);
        $this->addIf($noTrunc, '--no-trunc');
        $this->addIf($quiet, '--quiet');
        $this->addIf($image, null, $image);

        return $this->runCommand();
    }

    /**
     * Usage:  docker import [OPTIONS] file|URL|- [REPOSITORY[:TAG]].
     */
    public function import(
        string $url,
        string $repository,
        string $tag,
        /* Apply Dockerfile instruction to the created image */
        false|array $change = false,
        /* Set commit message for imported image */
        false|string $message = false,
        /* Set platform if server is multi-platform capable */
        false|string $platform = false,
    ): Process {
        $this->add('import');
        $this->addIf($change, '--change', $change);
        $this->addIf($message, '--message', $message);
        $this->addIf($platform, '--platform', $platform);
        $this->addIf($url, null, $url);
        $this->addIf($repository, null, $repository);
        $this->addIf($tag, null, $tag);

        return $this->runCommand();
    }

    /**
     * Usage:  docker inspect [OPTIONS] NAME|ID [NAME|ID...].
     */
    public function inspect(
        string $name,
        string $id,
        array $ids = [],
        /* Format output using a custom template: 'json': Print in JSON format 'TEMPLATE': Print output using the given Go template. Refer to https://docs.docker.com/go/formatting/ for more information about formatting output with templates */
        false|string $format = false,
        /* Display total file sizes if the type is container */
        bool $size = false,
        /* Return JSON for specified type */
        false|string $type = false,
    ): Process {
        $this->add('inspect');
        $this->addIf($format, '--format', $format);
        $this->addIf($size, '--size');
        $this->addIf($type, '--type', $type);
        $this->addIf($name, null, $name);
        $this->addIf($id, null, $id);
        $this->addIf($name, null, $name);
        $this->addIf($ids, null, $ids);

        return $this->runCommand();
    }

    /**
     * Usage:  docker kill [OPTIONS] CONTAINER [CONTAINER...].
     */
    public function kill(
        string $container,
        array $containers = [],
        /* Signal to send to the container */
        false|string $signal = false,
    ): Process {
        $this->add('kill');
        $this->addIf($signal, '--signal', $signal);
        $this->addIf($container, null, $container);
        $this->addIf($containers, null, $containers);

        return $this->runCommand();
    }

    /**
     * Usage:  docker load [OPTIONS].
     */
    public function load(
        /* Read from tar archive file, instead of STDIN */
        false|string $input = false,
        /* Suppress the load output */
        bool $quiet = false,
    ): Process {
        $this->add('load');
        $this->addIf($input, '--input', $input);
        $this->addIf($quiet, '--quiet');

        return $this->runCommand();
    }

    /**
     * Usage:  docker logs [OPTIONS] CONTAINER.
     */
    public function logs(
        string $container,
        /* Show extra details provided to logs */
        bool $details = false,
        /* Follow log output */
        bool $follow = false,
        /* Show logs since timestamp (e.g. "2013-01-02T13:23:37Z") or relative (e.g. "42m" for 42 minutes) */
        false|string $since = false,
        /* Number of lines to show from the end of the logs (default "all") */
        false|string $tail = false,
        /* Show timestamps */
        bool $timestamps = false,
        /* Show logs before a timestamp (e.g. "2013-01-02T13:23:37Z") or relative (e.g. "42m" for 42 minutes) */
        false|string $until = false,
    ): Process {
        $this->add('logs');
        $this->addIf($details, '--details');
        $this->addIf($follow, '--follow');
        $this->addIf($since, '--since', $since);
        $this->addIf($tail, '--tail', $tail);
        $this->addIf($timestamps, '--timestamps');
        $this->addIf($until, '--until', $until);
        $this->addIf($container, null, $container);

        return $this->runCommand();
    }

    /**
     * Usage:  docker pause CONTAINER [CONTAINER...].
     */
    public function pause(string $container, array $containers = []): Process
    {
        $this->add('pause');

        $this->addIf($container, null, $container);
        $this->addIf($containers, null, $containers);

        return $this->runCommand();
    }

    /**
     * Usage:  docker port CONTAINER [PRIVATE_PORT[/PROTO]].
     */
    public function port(string $container, string $private, string $port, string $proto): Process
    {
        $this->add('port');

        $this->addIf($container, null, $container);
        $this->addIf($private, null, $private);
        $this->addIf($port, null, $port);
        $this->addIf($proto, null, $proto);

        return $this->runCommand();
    }

    /**
     * Usage:  docker rename CONTAINER NEW_NAME.
     */
    public function rename(string $container, string $new, string $name): Process
    {
        $this->add('rename');

        $this->addIf($container, null, $container);
        $this->addIf($new, null, $new);
        $this->addIf($name, null, $name);

        return $this->runCommand();
    }

    /**
     * Usage:  docker restart [OPTIONS] CONTAINER [CONTAINER...].
     */
    public function restart(
        string $container,
        array $containers = [],
        /* Signal to send to the container */
        false|string $signal = false,
        /* Seconds to wait before killing the container */
        false|int $time = false,
    ): Process {
        $this->add('restart');
        $this->addIf($signal, '--signal', $signal);
        $this->addIf($time, '--time', $time);
        $this->addIf($container, null, $container);
        $this->addIf($containers, null, $containers);

        return $this->runCommand();
    }

    /**
     * Usage:  docker rm [OPTIONS] CONTAINER [CONTAINER...].
     */
    public function rm(
        string $container,
        array $containers = [],
        /* Force the removal of a running container (uses SIGKILL) */
        bool $force = false,
        /* Remove the specified link */
        bool $link = false,
        /* Remove anonymous volumes associated with the container */
        bool $volumes = false,
    ): Process {
        $this->add('rm');
        $this->addIf($force, '--force');
        $this->addIf($link, '--link');
        $this->addIf($volumes, '--volumes');
        $this->addIf($container, null, $container);
        $this->addIf($containers, null, $containers);

        return $this->runCommand();
    }

    /**
     * Usage:  docker rmi [OPTIONS] IMAGE [IMAGE...].
     */
    public function rmi(
        string $image,
        array $images = [],
        /* Force removal of the image */
        bool $force = false,
        /* Do not delete untagged parents */
        bool $noPrune = false,
    ): Process {
        $this->add('rmi');
        $this->addIf($force, '--force');
        $this->addIf($noPrune, '--no-prune');
        $this->addIf($image, null, $image);
        $this->addIf($images, null, $images);

        return $this->runCommand();
    }

    /**
     * Usage:  docker save [OPTIONS] IMAGE [IMAGE...].
     */
    public function save(
        string $image,
        array $images = [],
        /* Write to a file, instead of STDOUT */
        false|string $output = false,
    ): Process {
        $this->add('save');
        $this->addIf($output, '--output', $output);
        $this->addIf($image, null, $image);
        $this->addIf($images, null, $images);

        return $this->runCommand();
    }

    /**
     * Usage:  docker start [OPTIONS] CONTAINER [CONTAINER...].
     */
    public function start(
        string $container,
        array $containers = [],
        /* Attach STDOUT/STDERR and forward signals */
        bool $attach = false,
        /* Override the key sequence for detaching a container */
        false|string $detachKeys = false,
        /* Attach container's STDIN */
        bool $interactive = false,
    ): Process {
        $this->add('start');
        $this->addIf($attach, '--attach');
        $this->addIf($detachKeys, '--detach-keys', $detachKeys);
        $this->addIf($interactive, '--interactive');
        $this->addIf($container, null, $container);
        $this->addIf($containers, null, $containers);

        return $this->runCommand();
    }

    /**
     * Usage:  docker stats [OPTIONS] [CONTAINER...].
     */
    public function stats(
        array $containers = [],
        /* Show all containers (default shows just running) */
        bool $all = false,
        /* Format output using a custom template: 'table': Print output in table format with column headers (default) 'table TEMPLATE': Print output in table format using the given Go template 'json': Print in JSON format 'TEMPLATE': Print output using the given Go template. Refer to https://docs.docker.com/go/formatting/ for more information about formatting output with templates */
        false|string $format = false,
        /* Disable streaming stats and only pull the first result */
        bool $noStream = false,
        /* Do not truncate output */
        bool $noTrunc = false,
    ): Process {
        $this->add('stats');
        $this->addIf($all, '--all');
        $this->addIf($format, '--format', $format);
        $this->addIf($noStream, '--no-stream');
        $this->addIf($noTrunc, '--no-trunc');
        $this->addIf($containers, null, $containers);

        return $this->runCommand();
    }

    /**
     * Usage:  docker stop [OPTIONS] CONTAINER [CONTAINER...].
     */
    public function stop(
        string $container,
        array $containers = [],
        /* Signal to send to the container */
        false|string $signal = false,
        /* Seconds to wait before killing the container */
        false|int $time = false,
    ): Process {
        $this->add('stop');
        $this->addIf($signal, '--signal', $signal);
        $this->addIf($time, '--time', $time);
        $this->addIf($container, null, $container);
        $this->addIf($containers, null, $containers);

        return $this->runCommand();
    }

    /**
     * Usage:  docker tag SOURCE_IMAGE[:TAG] TARGET_IMAGE[:TAG].
     */
    public function tag(string $source, string $image, string $tag, string $target): Process
    {
        $this->add('tag');

        $this->addIf($source, null, $source);
        $this->addIf($image, null, $image);
        $this->addIf($tag, null, $tag);
        $this->addIf($target, null, $target);
        $this->addIf($image, null, $image);
        $this->addIf($tag, null, $tag);

        return $this->runCommand();
    }

    /**
     * Usage:  docker top CONTAINER [ps OPTIONS].
     */
    public function top(string $container): Process
    {
        $this->add('top');

        $this->addIf($container, null, $container);

        return $this->runCommand();
    }

    /**
     * Usage:  docker unpause CONTAINER [CONTAINER...].
     */
    public function unpause(string $container, array $containers = []): Process
    {
        $this->add('unpause');

        $this->addIf($container, null, $container);
        $this->addIf($containers, null, $containers);

        return $this->runCommand();
    }

    /**
     * Usage:  docker update [OPTIONS] CONTAINER [CONTAINER...].
     */
    public function update(
        string $container,
        array $containers = [],
        /* Block IO (relative weight), between 10 and 1000, or 0 to disable (default 0) */
        false|int $blkioWeight = false,
        /* Limit CPU CFS (Completely Fair Scheduler) period */
        false|int $cpuPeriod = false,
        /* Limit CPU CFS (Completely Fair Scheduler) quota */
        false|int $cpuQuota = false,
        /* Limit the CPU real-time period in microseconds */
        false|int $cpuRtPeriod = false,
        /* Limit the CPU real-time runtime in microseconds */
        false|int $cpuRtRuntime = false,
        /* CPU shares (relative weight) */
        false|int $cpuShares = false,
        /* Number of CPUs */
        false|int $cpus = false,
        /* CPUs in which to allow execution (0-3, 0,1) */
        false|string $cpusetCpus = false,
        /* MEMs in which to allow execution (0-3, 0,1) */
        false|string $cpusetMems = false,
        /* Memory limit */
        false|int $memory = false,
        /* Memory soft limit */
        false|int $memoryReservation = false,
        /* Swap limit equal to memory plus swap: -1 to enable unlimited swap */
        false|int $memorySwap = false,
        /* Tune container pids limit (set -1 for unlimited) */
        false|int $pidsLimit = false,
        /* Restart policy to apply when a container exits */
        false|string $restart = false,
    ): Process {
        $this->add('update');
        $this->addIf($blkioWeight, '--blkio-weight', $blkioWeight);
        $this->addIf($cpuPeriod, '--cpu-period', $cpuPeriod);
        $this->addIf($cpuQuota, '--cpu-quota', $cpuQuota);
        $this->addIf($cpuRtPeriod, '--cpu-rt-period', $cpuRtPeriod);
        $this->addIf($cpuRtRuntime, '--cpu-rt-runtime', $cpuRtRuntime);
        $this->addIf($cpuShares, '--cpu-shares', $cpuShares);
        $this->addIf($cpus, '--cpus', $cpus);
        $this->addIf($cpusetCpus, '--cpuset-cpus', $cpusetCpus);
        $this->addIf($cpusetMems, '--cpuset-mems', $cpusetMems);
        $this->addIf($memory, '--memory', $memory);
        $this->addIf($memoryReservation, '--memory-reservation', $memoryReservation);
        $this->addIf($memorySwap, '--memory-swap', $memorySwap);
        $this->addIf($pidsLimit, '--pids-limit', $pidsLimit);
        $this->addIf($restart, '--restart', $restart);
        $this->addIf($container, null, $container);
        $this->addIf($containers, null, $containers);

        return $this->runCommand();
    }

    /**
     * Usage:  docker wait CONTAINER [CONTAINER...].
     */
    public function wait(string $container, array $containers = []): Process
    {
        $this->add('wait');

        $this->addIf($container, null, $container);
        $this->addIf($containers, null, $containers);

        return $this->runCommand();
    }
}

class DockerBuilder
{
    public function __construct(
        private readonly Docker $docker,
        /** Override the configured builder instance (default "default") */
        private readonly false|string $builder = false,
    ) {
        $this->docker->add('builder');

        $this->docker->addIf($this->builder, '--builder', $this->builder);
    }

    public function imagetools(
        /* Override the configured builder instance (default "default") */
        false|string $builder = false,
    ): DockerBuilderImagetools {
        return new DockerBuilderImagetools(docker: $this->docker, builder: $builder);
    }

    /**
     * Usage:  docker buildx bake [OPTIONS] [TARGET...].
     */
    public function bake(
        array $targets = [],
        /* Override the configured builder instance (default "default") */
        false|string $builder = false,
        /* Build definition file */
        false|array $file = false,
        /* Shorthand for "--set=*.output=type=docker" */
        bool $load = false,
        /* Write build result metadata to the file */
        false|string $metadataFile = false,
        /* Do not use cache when building the image */
        bool $noCache = false,
        /* Print the options without building */
        bool $print = false,
        /* Set type of progress output ("auto", "plain", "tty"). Use plain to show container output (default "auto") */
        false|string $progress = false,
        /* Shorthand for "--set=*.attest=type=provenance" */
        false|string $provenance = false,
        /* Always attempt to pull all referenced images */
        bool $pull = false,
        /* Shorthand for "--set=*.output=type=registry" */
        bool $push = false,
        /* Shorthand for "--set=*.attest=type=sbom" */
        false|string $sbom = false,
        /* Override target value (e.g., "targetpattern.key=value") */
        false|array $set = false,
    ): Process {
        $this->docker->add('bake');
        $this->docker->addIf($builder, '--builder', $builder);
        $this->docker->addIf($file, '--file', $file);
        $this->docker->addIf($load, '--load');
        $this->docker->addIf($metadataFile, '--metadata-file', $metadataFile);
        $this->docker->addIf($noCache, '--no-cache');
        $this->docker->addIf($print, '--print');
        $this->docker->addIf($progress, '--progress', $progress);
        $this->docker->addIf($provenance, '--provenance', $provenance);
        $this->docker->addIf($pull, '--pull');
        $this->docker->addIf($push, '--push');
        $this->docker->addIf($sbom, '--sbom', $sbom);
        $this->docker->addIf($set, '--set', $set);
        $this->docker->addIf($targets, null, $targets);

        return $this->docker->runCommand();
    }

    /**
     * Usage:  docker buildx build [OPTIONS] PATH | URL | -.
     */
    public function build(
        string $path,
        string $url,
        /* strings Add a custom host-to-IP mapping (format: "host:ip") */
        bool $addHost = false,
        /* strings Allow extra privileged entitlement (e.g., "network.host", "security.insecure") */
        bool $allow = false,
        /* Add annotation to the image */
        false|array $annotation = false,
        /* Attestation parameters (format: "type=sbom,generator=image") */
        false|array $attest = false,
        /* Set build-time variables */
        false|array $buildArg = false,
        /* Additional build contexts (e.g., name=path) */
        false|array $buildContext = false,
        /* Override the configured builder instance (default "default") */
        false|string $builder = false,
        /* External cache sources (e.g., "user/app:cache", "type=local,src=path/to/dir") */
        false|array $cacheFrom = false,
        /* Cache export destinations (e.g., "user/app:cache", "type=local,dest=path/to/dir") */
        false|array $cacheTo = false,
        /* Set the parent cgroup for the "RUN" instructions during build */
        false|string $cgroupParent = false,
        /* Name of the Dockerfile (default: "PATH/Dockerfile") */
        false|string $file = false,
        /* Write the image ID to the file */
        false|string $iidfile = false,
        /* Set metadata for an image */
        false|array $label = false,
        /* Shorthand for "--output=type=docker" */
        bool $load = false,
        /* Write build result metadata to the file */
        false|string $metadataFile = false,
        /* Set the networking mode for the "RUN" instructions during build (default "default") */
        false|string $network = false,
        /* Do not use cache when building the image */
        bool $noCache = false,
        /* Do not cache specified stages */
        false|array $noCacheFilter = false,
        /* Output destination (format: "type=local,dest=path") */
        false|array $output = false,
        /* Set target platform for build */
        false|array $platform = false,
        /* Set type of progress output ("auto", "plain", "tty"). Use plain to show container output (default "auto") */
        false|string $progress = false,
        /* Shorthand for "--attest=type=provenance" */
        false|string $provenance = false,
        /* Always attempt to pull all referenced images */
        bool $pull = false,
        /* Shorthand for "--output=type=registry" */
        bool $push = false,
        /* Suppress the build output and print image ID on success */
        bool $quiet = false,
        /* Shorthand for "--attest=type=sbom" */
        false|string $sbom = false,
        /* Secret to expose to the build (format: "id=mysecret[,src=/local/secret]") */
        false|array $secret = false,
        /* Size of "/dev/shm" */
        false|int $shmSize = false,
        /* SSH agent socket or keys to expose to the build (format: "default|<id>[=<socket>|<key>[,<key>]]") */
        false|array $ssh = false,
        /* Name and optionally a tag (format: "name:tag") */
        false|array $tag = false,
        /* Set the target build stage to build */
        false|string $target = false,
        /* Ulimit options (default []) */
        false|int $ulimit = false,
    ): Process {
        $this->docker->add('build');
        $this->docker->addIf($addHost, '--add-host');
        $this->docker->addIf($allow, '--allow');
        $this->docker->addIf($annotation, '--annotation', $annotation);
        $this->docker->addIf($attest, '--attest', $attest);
        $this->docker->addIf($buildArg, '--build-arg', $buildArg);
        $this->docker->addIf($buildContext, '--build-context', $buildContext);
        $this->docker->addIf($builder, '--builder', $builder);
        $this->docker->addIf($cacheFrom, '--cache-from', $cacheFrom);
        $this->docker->addIf($cacheTo, '--cache-to', $cacheTo);
        $this->docker->addIf($cgroupParent, '--cgroup-parent', $cgroupParent);
        $this->docker->addIf($file, '--file', $file);
        $this->docker->addIf($iidfile, '--iidfile', $iidfile);
        $this->docker->addIf($label, '--label', $label);
        $this->docker->addIf($load, '--load');
        $this->docker->addIf($metadataFile, '--metadata-file', $metadataFile);
        $this->docker->addIf($network, '--network', $network);
        $this->docker->addIf($noCache, '--no-cache');
        $this->docker->addIf($noCacheFilter, '--no-cache-filter', $noCacheFilter);
        $this->docker->addIf($output, '--output', $output);
        $this->docker->addIf($platform, '--platform', $platform);
        $this->docker->addIf($progress, '--progress', $progress);
        $this->docker->addIf($provenance, '--provenance', $provenance);
        $this->docker->addIf($pull, '--pull');
        $this->docker->addIf($push, '--push');
        $this->docker->addIf($quiet, '--quiet');
        $this->docker->addIf($sbom, '--sbom', $sbom);
        $this->docker->addIf($secret, '--secret', $secret);
        $this->docker->addIf($shmSize, '--shm-size', $shmSize);
        $this->docker->addIf($ssh, '--ssh', $ssh);
        $this->docker->addIf($tag, '--tag', $tag);
        $this->docker->addIf($target, '--target', $target);
        $this->docker->addIf($ulimit, '--ulimit', $ulimit);
        $this->docker->addIf($path, null, $path);
        $this->docker->addIf($url, null, $url);

        return $this->docker->runCommand();
    }

    /**
     * Usage:  docker buildx create [OPTIONS] [CONTEXT|ENDPOINT].
     */
    public function create(
        string $context,
        string $endpoint,
        /* Append a node to builder instead of changing it */
        bool $append = false,
        /* Boot builder after creation */
        bool $bootstrap = false,
        /* Flags for buildkitd daemon */
        false|string $buildkitdFlags = false,
        /* BuildKit config file */
        false|string $config = false,
        /* Driver to use (available: "docker-container", "kubernetes", "remote") */
        false|string $driver = false,
        /* Options for the driver */
        false|array $driverOpt = false,
        /* Remove a node from builder instead of changing it */
        bool $leave = false,
        /* Builder instance name */
        false|string $name = false,
        /* Create/modify node with given name */
        false|string $node = false,
        /* Fixed platforms for current node */
        false|array $platform = false,
        /* Set the current builder instance */
        bool $use = false,
    ): Process {
        $this->docker->add('create');
        $this->docker->addIf($append, '--append');
        $this->docker->addIf($bootstrap, '--bootstrap');
        $this->docker->addIf($buildkitdFlags, '--buildkitd-flags', $buildkitdFlags);
        $this->docker->addIf($config, '--config', $config);
        $this->docker->addIf($driver, '--driver', $driver);
        $this->docker->addIf($driverOpt, '--driver-opt', $driverOpt);
        $this->docker->addIf($leave, '--leave');
        $this->docker->addIf($name, '--name', $name);
        $this->docker->addIf($node, '--node', $node);
        $this->docker->addIf($platform, '--platform', $platform);
        $this->docker->addIf($use, '--use');
        $this->docker->addIf($context, null, $context);
        $this->docker->addIf($endpoint, null, $endpoint);

        return $this->docker->runCommand();
    }

    /**
     * Usage:  docker buildx du.
     */
    public function du(
        /* Override the configured builder instance (default "default") */
        false|string $builder = false,
        /* Provide filter values */
        false|array $filter = false,
        /* Provide a more verbose output */
        bool $verbose = false,
    ): Process {
        $this->docker->add('du');
        $this->docker->addIf($builder, '--builder', $builder);
        $this->docker->addIf($filter, '--filter', $filter);
        $this->docker->addIf($verbose, '--verbose');

        return $this->docker->runCommand();
    }

    /**
     * Usage:  docker buildx inspect [NAME].
     */
    public function inspect(
        string $name,
        /* Ensure builder has booted before inspecting */
        bool $bootstrap = false,
        /* Override the configured builder instance (default "default") */
        false|string $builder = false,
    ): Process {
        $this->docker->add('inspect');
        $this->docker->addIf($bootstrap, '--bootstrap');
        $this->docker->addIf($builder, '--builder', $builder);
        $this->docker->addIf($name, null, $name);

        return $this->docker->runCommand();
    }

    /**
     * Usage:  docker buildx ls.
     */
    public function ls(): Process
    {
        $this->docker->add('ls');

        return $this->docker->runCommand();
    }

    /**
     * Usage:  docker buildx prune.
     */
    public function prune(
        /* Include internal/frontend images */
        bool $all = false,
        /* Override the configured builder instance (default "default") */
        false|string $builder = false,
        /* Provide filter values (e.g., "until=24h") */
        false|array $filter = false,
        /* Do not prompt for confirmation */
        bool $force = false,
        /* Amount of disk space to keep for cache */
        false|int $keepStorage = false,
        /* Provide a more verbose output */
        bool $verbose = false,
    ): Process {
        $this->docker->add('prune');
        $this->docker->addIf($all, '--all');
        $this->docker->addIf($builder, '--builder', $builder);
        $this->docker->addIf($filter, '--filter', $filter);
        $this->docker->addIf($force, '--force');
        $this->docker->addIf($keepStorage, '--keep-storage', $keepStorage);
        $this->docker->addIf($verbose, '--verbose');

        return $this->docker->runCommand();
    }

    /**
     * Usage:  docker buildx rm [NAME].
     */
    public function rm(
        string $name,
        /* Remove all inactive builders */
        bool $allInactive = false,
        /* Override the configured builder instance (default "default") */
        false|string $builder = false,
        /* Do not prompt for confirmation */
        bool $force = false,
        /* Keep the buildkitd daemon running */
        bool $keepDaemon = false,
        /* Keep BuildKit state */
        bool $keepState = false,
    ): Process {
        $this->docker->add('rm');
        $this->docker->addIf($allInactive, '--all-inactive');
        $this->docker->addIf($builder, '--builder', $builder);
        $this->docker->addIf($force, '--force');
        $this->docker->addIf($keepDaemon, '--keep-daemon');
        $this->docker->addIf($keepState, '--keep-state');
        $this->docker->addIf($name, null, $name);

        return $this->docker->runCommand();
    }

    /**
     * Usage:  docker buildx stop [NAME].
     */
    public function stop(
        string $name,
        /* Override the configured builder instance (default "default") */
        false|string $builder = false,
    ): Process {
        $this->docker->add('stop');
        $this->docker->addIf($builder, '--builder', $builder);
        $this->docker->addIf($name, null, $name);

        return $this->docker->runCommand();
    }

    /**
     * Usage:  docker buildx use [OPTIONS] NAME.
     */
    public function use(
        string $name,
        /* Override the configured builder instance (default "default") */
        false|string $builder = false,
        /* Set builder as default for current context */
        bool $default = false,
        /* Builder persists context changes */
        bool $global = false,
    ): Process {
        $this->docker->add('use');
        $this->docker->addIf($builder, '--builder', $builder);
        $this->docker->addIf($default, '--default');
        $this->docker->addIf($global, '--global');
        $this->docker->addIf($name, null, $name);

        return $this->docker->runCommand();
    }

    /**
     * Usage:  docker buildx version.
     */
    public function version(): Process
    {
        $this->docker->add('version');

        return $this->docker->runCommand();
    }
}

class DockerBuildx
{
    public function __construct(
        private readonly Docker $docker,
        /** Override the configured builder instance */
        private readonly false|string $builder = false,
    ) {
        $this->docker->add('buildx');

        $this->docker->addIf($this->builder, '--builder', $this->builder);
    }

    public function imagetools(
        /* Override the configured builder instance */
        false|string $builder = false,
    ): DockerBuildxImagetools {
        return new DockerBuildxImagetools(docker: $this->docker, builder: $builder);
    }

    /**
     * Usage:  docker buildx bake [OPTIONS] [TARGET...].
     */
    public function bake(
        array $targets = [],
        /* Override the configured builder instance */
        false|string $builder = false,
        /* Build definition file */
        false|array $file = false,
        /* Shorthand for "--set=*.output=type=docker" */
        bool $load = false,
        /* Write build result metadata to the file */
        false|string $metadataFile = false,
        /* Do not use cache when building the image */
        bool $noCache = false,
        /* Print the options without building */
        bool $print = false,
        /* Set type of progress output ("auto", "plain", "tty"). Use plain to show container output (default "auto") */
        false|string $progress = false,
        /* Shorthand for "--set=*.attest=type=provenance" */
        false|string $provenance = false,
        /* Always attempt to pull all referenced images */
        bool $pull = false,
        /* Shorthand for "--set=*.output=type=registry" */
        bool $push = false,
        /* Shorthand for "--set=*.attest=type=sbom" */
        false|string $sbom = false,
        /* Override target value (e.g., "targetpattern.key=value") */
        false|array $set = false,
    ): Process {
        $this->docker->add('bake');
        $this->docker->addIf($builder, '--builder', $builder);
        $this->docker->addIf($file, '--file', $file);
        $this->docker->addIf($load, '--load');
        $this->docker->addIf($metadataFile, '--metadata-file', $metadataFile);
        $this->docker->addIf($noCache, '--no-cache');
        $this->docker->addIf($print, '--print');
        $this->docker->addIf($progress, '--progress', $progress);
        $this->docker->addIf($provenance, '--provenance', $provenance);
        $this->docker->addIf($pull, '--pull');
        $this->docker->addIf($push, '--push');
        $this->docker->addIf($sbom, '--sbom', $sbom);
        $this->docker->addIf($set, '--set', $set);
        $this->docker->addIf($targets, null, $targets);

        return $this->docker->runCommand();
    }

    /**
     * Usage:  docker buildx build [OPTIONS] PATH | URL | -.
     */
    public function build(
        string $path,
        string $url,
        /* strings Add a custom host-to-IP mapping (format: "host:ip") */
        bool $addHost = false,
        /* strings Allow extra privileged entitlement (e.g., "network.host", "security.insecure") */
        bool $allow = false,
        /* Add annotation to the image */
        false|array $annotation = false,
        /* Attestation parameters (format: "type=sbom,generator=image") */
        false|array $attest = false,
        /* Set build-time variables */
        false|array $buildArg = false,
        /* Additional build contexts (e.g., name=path) */
        false|array $buildContext = false,
        /* Override the configured builder instance */
        false|string $builder = false,
        /* External cache sources (e.g., "user/app:cache", "type=local,src=path/to/dir") */
        false|array $cacheFrom = false,
        /* Cache export destinations (e.g., "user/app:cache", "type=local,dest=path/to/dir") */
        false|array $cacheTo = false,
        /* Set the parent cgroup for the "RUN" instructions during build */
        false|string $cgroupParent = false,
        /* Name of the Dockerfile (default: "PATH/Dockerfile") */
        false|string $file = false,
        /* Write the image ID to the file */
        false|string $iidfile = false,
        /* Set metadata for an image */
        false|array $label = false,
        /* Shorthand for "--output=type=docker" */
        bool $load = false,
        /* Write build result metadata to the file */
        false|string $metadataFile = false,
        /* Set the networking mode for the "RUN" instructions during build (default "default") */
        false|string $network = false,
        /* Do not use cache when building the image */
        bool $noCache = false,
        /* Do not cache specified stages */
        false|array $noCacheFilter = false,
        /* Output destination (format: "type=local,dest=path") */
        false|array $output = false,
        /* Set target platform for build */
        false|array $platform = false,
        /* Set type of progress output ("auto", "plain", "tty"). Use plain to show container output (default "auto") */
        false|string $progress = false,
        /* Shorthand for "--attest=type=provenance" */
        false|string $provenance = false,
        /* Always attempt to pull all referenced images */
        bool $pull = false,
        /* Shorthand for "--output=type=registry" */
        bool $push = false,
        /* Suppress the build output and print image ID on success */
        bool $quiet = false,
        /* Shorthand for "--attest=type=sbom" */
        false|string $sbom = false,
        /* Secret to expose to the build (format: "id=mysecret[,src=/local/secret]") */
        false|array $secret = false,
        /* Size of "/dev/shm" */
        false|int $shmSize = false,
        /* SSH agent socket or keys to expose to the build (format: "default|<id>[=<socket>|<key>[,<key>]]") */
        false|array $ssh = false,
        /* Name and optionally a tag (format: "name:tag") */
        false|array $tag = false,
        /* Set the target build stage to build */
        false|string $target = false,
        /* Ulimit options (default []) */
        false|int $ulimit = false,
    ): Process {
        $this->docker->add('build');
        $this->docker->addIf($addHost, '--add-host');
        $this->docker->addIf($allow, '--allow');
        $this->docker->addIf($annotation, '--annotation', $annotation);
        $this->docker->addIf($attest, '--attest', $attest);
        $this->docker->addIf($buildArg, '--build-arg', $buildArg);
        $this->docker->addIf($buildContext, '--build-context', $buildContext);
        $this->docker->addIf($builder, '--builder', $builder);
        $this->docker->addIf($cacheFrom, '--cache-from', $cacheFrom);
        $this->docker->addIf($cacheTo, '--cache-to', $cacheTo);
        $this->docker->addIf($cgroupParent, '--cgroup-parent', $cgroupParent);
        $this->docker->addIf($file, '--file', $file);
        $this->docker->addIf($iidfile, '--iidfile', $iidfile);
        $this->docker->addIf($label, '--label', $label);
        $this->docker->addIf($load, '--load');
        $this->docker->addIf($metadataFile, '--metadata-file', $metadataFile);
        $this->docker->addIf($network, '--network', $network);
        $this->docker->addIf($noCache, '--no-cache');
        $this->docker->addIf($noCacheFilter, '--no-cache-filter', $noCacheFilter);
        $this->docker->addIf($output, '--output', $output);
        $this->docker->addIf($platform, '--platform', $platform);
        $this->docker->addIf($progress, '--progress', $progress);
        $this->docker->addIf($provenance, '--provenance', $provenance);
        $this->docker->addIf($pull, '--pull');
        $this->docker->addIf($push, '--push');
        $this->docker->addIf($quiet, '--quiet');
        $this->docker->addIf($sbom, '--sbom', $sbom);
        $this->docker->addIf($secret, '--secret', $secret);
        $this->docker->addIf($shmSize, '--shm-size', $shmSize);
        $this->docker->addIf($ssh, '--ssh', $ssh);
        $this->docker->addIf($tag, '--tag', $tag);
        $this->docker->addIf($target, '--target', $target);
        $this->docker->addIf($ulimit, '--ulimit', $ulimit);
        $this->docker->addIf($path, null, $path);
        $this->docker->addIf($url, null, $url);

        return $this->docker->runCommand();
    }

    /**
     * Usage:  docker buildx create [OPTIONS] [CONTEXT|ENDPOINT].
     */
    public function create(
        string $context,
        string $endpoint,
        /* Append a node to builder instead of changing it */
        bool $append = false,
        /* Boot builder after creation */
        bool $bootstrap = false,
        /* Flags for buildkitd daemon */
        false|string $buildkitdFlags = false,
        /* BuildKit config file */
        false|string $config = false,
        /* Driver to use (available: "docker-container", "kubernetes", "remote") */
        false|string $driver = false,
        /* Options for the driver */
        false|array $driverOpt = false,
        /* Remove a node from builder instead of changing it */
        bool $leave = false,
        /* Builder instance name */
        false|string $name = false,
        /* Create/modify node with given name */
        false|string $node = false,
        /* Fixed platforms for current node */
        false|array $platform = false,
        /* Set the current builder instance */
        bool $use = false,
    ): Process {
        $this->docker->add('create');
        $this->docker->addIf($append, '--append');
        $this->docker->addIf($bootstrap, '--bootstrap');
        $this->docker->addIf($buildkitdFlags, '--buildkitd-flags', $buildkitdFlags);
        $this->docker->addIf($config, '--config', $config);
        $this->docker->addIf($driver, '--driver', $driver);
        $this->docker->addIf($driverOpt, '--driver-opt', $driverOpt);
        $this->docker->addIf($leave, '--leave');
        $this->docker->addIf($name, '--name', $name);
        $this->docker->addIf($node, '--node', $node);
        $this->docker->addIf($platform, '--platform', $platform);
        $this->docker->addIf($use, '--use');
        $this->docker->addIf($context, null, $context);
        $this->docker->addIf($endpoint, null, $endpoint);

        return $this->docker->runCommand();
    }

    /**
     * Usage:  docker buildx du.
     */
    public function du(
        /* Override the configured builder instance */
        false|string $builder = false,
        /* Provide filter values */
        false|array $filter = false,
        /* Provide a more verbose output */
        bool $verbose = false,
    ): Process {
        $this->docker->add('du');
        $this->docker->addIf($builder, '--builder', $builder);
        $this->docker->addIf($filter, '--filter', $filter);
        $this->docker->addIf($verbose, '--verbose');

        return $this->docker->runCommand();
    }

    /**
     * Usage:  docker buildx inspect [NAME].
     */
    public function inspect(
        string $name,
        /* Ensure builder has booted before inspecting */
        bool $bootstrap = false,
        /* Override the configured builder instance */
        false|string $builder = false,
    ): Process {
        $this->docker->add('inspect');
        $this->docker->addIf($bootstrap, '--bootstrap');
        $this->docker->addIf($builder, '--builder', $builder);
        $this->docker->addIf($name, null, $name);

        return $this->docker->runCommand();
    }

    /**
     * Usage:  docker buildx ls.
     */
    public function ls(): Process
    {
        $this->docker->add('ls');

        return $this->docker->runCommand();
    }

    /**
     * Usage:  docker buildx prune.
     */
    public function prune(
        /* Include internal/frontend images */
        bool $all = false,
        /* Override the configured builder instance */
        false|string $builder = false,
        /* Provide filter values (e.g., "until=24h") */
        false|array $filter = false,
        /* Do not prompt for confirmation */
        bool $force = false,
        /* Amount of disk space to keep for cache */
        false|int $keepStorage = false,
        /* Provide a more verbose output */
        bool $verbose = false,
    ): Process {
        $this->docker->add('prune');
        $this->docker->addIf($all, '--all');
        $this->docker->addIf($builder, '--builder', $builder);
        $this->docker->addIf($filter, '--filter', $filter);
        $this->docker->addIf($force, '--force');
        $this->docker->addIf($keepStorage, '--keep-storage', $keepStorage);
        $this->docker->addIf($verbose, '--verbose');

        return $this->docker->runCommand();
    }

    /**
     * Usage:  docker buildx rm [NAME].
     */
    public function rm(
        string $name,
        /* Remove all inactive builders */
        bool $allInactive = false,
        /* Override the configured builder instance */
        false|string $builder = false,
        /* Do not prompt for confirmation */
        bool $force = false,
        /* Keep the buildkitd daemon running */
        bool $keepDaemon = false,
        /* Keep BuildKit state */
        bool $keepState = false,
    ): Process {
        $this->docker->add('rm');
        $this->docker->addIf($allInactive, '--all-inactive');
        $this->docker->addIf($builder, '--builder', $builder);
        $this->docker->addIf($force, '--force');
        $this->docker->addIf($keepDaemon, '--keep-daemon');
        $this->docker->addIf($keepState, '--keep-state');
        $this->docker->addIf($name, null, $name);

        return $this->docker->runCommand();
    }

    /**
     * Usage:  docker buildx stop [NAME].
     */
    public function stop(
        string $name,
        /* Override the configured builder instance */
        false|string $builder = false,
    ): Process {
        $this->docker->add('stop');
        $this->docker->addIf($builder, '--builder', $builder);
        $this->docker->addIf($name, null, $name);

        return $this->docker->runCommand();
    }

    /**
     * Usage:  docker buildx use [OPTIONS] NAME.
     */
    public function use(
        string $name,
        /* Override the configured builder instance */
        false|string $builder = false,
        /* Set builder as default for current context */
        bool $default = false,
        /* Builder persists context changes */
        bool $global = false,
    ): Process {
        $this->docker->add('use');
        $this->docker->addIf($builder, '--builder', $builder);
        $this->docker->addIf($default, '--default');
        $this->docker->addIf($global, '--global');
        $this->docker->addIf($name, null, $name);

        return $this->docker->runCommand();
    }

    /**
     * Usage:  docker buildx version.
     */
    public function version(): Process
    {
        $this->docker->add('version');

        return $this->docker->runCommand();
    }
}

class DockerCompose
{
    public function __construct(
        private readonly Docker $docker,
        /** Control when to print ANSI control characters ("never"|"always"|"auto") (default "auto") */
        private readonly false|string $ansi = false,
        /** Run compose in backward compatibility mode */
        private readonly bool $compatibility = false,
        /** Execute command in dry run mode */
        private readonly bool $dryRun = false,
        /** Specify an alternate environment file. */
        private readonly false|array $envFile = false,
        /** Compose configuration files */
        private readonly false|array $file = false,
        /** Control max parallelism, -1 for unlimited (default -1) */
        private readonly false|int $parallel = false,
        /** Specify a profile to enable */
        private readonly false|array $profile = false,
        /** Set type of progress output (auto, tty, plain, quiet) (default "auto") */
        private readonly false|string $progress = false,
        /** Specify an alternate working directory (default: the path of the, first specified, Compose file) */
        private readonly false|string $projectDirectory = false,
        /** Project name */
        private readonly false|string $projectName = false,
    ) {
        $this->docker->add('compose');

        $this->docker->addIf($this->ansi, '--ansi', $this->ansi);
        $this->docker->addIf($this->compatibility, '--compatibility');
        $this->docker->addIf($this->dryRun, '--dry-run');
        $this->docker->addIf($this->envFile, '--env-file', $this->envFile);
        $this->docker->addIf($this->file, '--file', $this->file);
        $this->docker->addIf($this->parallel, '--parallel', $this->parallel);
        $this->docker->addIf($this->profile, '--profile', $this->profile);
        $this->docker->addIf($this->progress, '--progress', $this->progress);
        $this->docker->addIf($this->projectDirectory, '--project-directory', $this->projectDirectory);
        $this->docker->addIf($this->projectName, '--project-name', $this->projectName);
    }

    /**
     * Usage:  docker compose attach [OPTIONS] SERVICE.
     */
    public function attach(
        string $service,
        /* Override the key sequence for detaching from a container. */
        false|string $detachKeys = false,
        /* Execute command in dry run mode */
        bool $dryRun = false,
        /* index of the container if service has multiple replicas. */
        false|int $index = false,
        /* Do not attach STDIN */
        bool $noStdin = false,
        /* Proxy all received signals to the process (default true) */
        bool $sigProxy = false,
    ): Process {
        $this->docker->add('attach');
        $this->docker->addIf($detachKeys, '--detach-keys', $detachKeys);
        $this->docker->addIf($dryRun, '--dry-run');
        $this->docker->addIf($index, '--index', $index);
        $this->docker->addIf($noStdin, '--no-stdin');
        $this->docker->addIf($sigProxy, '--sig-proxy');
        $this->docker->addIf($service, null, $service);

        return $this->docker->runCommand();
    }

    /**
     * Usage:  docker compose build [OPTIONS] [SERVICE...].
     */
    public function build(
        array $services = [],
        /* Set build-time variables for services. */
        false|array $buildArg = false,
        /* Set builder to use. */
        false|string $builder = false,
        /* Execute command in dry run mode */
        bool $dryRun = false,
        /* Set memory limit for the build container. Not supported by BuildKit. */
        false|int $memory = false,
        /* Do not use cache when building the image */
        bool $noCache = false,
        /* Always attempt to pull a newer version of the image. */
        bool $pull = false,
        /* Push service images. */
        bool $push = false,
        /* Don't print anything to STDOUT */
        bool $quiet = false,
        /* Set SSH authentications used when building service images. (use 'default' for using your default SSH Agent) */
        false|string $ssh = false,
        /* Also build dependencies (transitively). */
        bool $withDependencies = false,
    ): Process {
        $this->docker->add('build');
        $this->docker->addIf($buildArg, '--build-arg', $buildArg);
        $this->docker->addIf($builder, '--builder', $builder);
        $this->docker->addIf($dryRun, '--dry-run');
        $this->docker->addIf($memory, '--memory', $memory);
        $this->docker->addIf($noCache, '--no-cache');
        $this->docker->addIf($pull, '--pull');
        $this->docker->addIf($push, '--push');
        $this->docker->addIf($quiet, '--quiet');
        $this->docker->addIf($ssh, '--ssh', $ssh);
        $this->docker->addIf($withDependencies, '--with-dependencies');
        $this->docker->addIf($services, null, $services);

        return $this->docker->runCommand();
    }

    /**
     * Usage:  docker compose config [OPTIONS] [SERVICE...].
     */
    public function config(
        /* Print the service names, one per line. */
        bool $services = false,
        /* Execute command in dry run mode */
        bool $dryRun = false,
        /* Format the output. Values: [yaml | json] (default "yaml") */
        false|string $format = false,
        /* Print the service config hash, one per line. */
        false|string $hash = false,
        /* Print the image names, one per line. */
        bool $images = false,
        /* Don't check model consistency - warning: may produce invalid Compose output */
        bool $noConsistency = false,
        /* Don't interpolate environment variables. */
        bool $noInterpolate = false,
        /* Don't normalize compose model. */
        bool $noNormalize = false,
        /* Don't resolve file paths. */
        bool $noPathResolution = false,
        /* Save to file (default to stdout) */
        false|string $output = false,
        /* Print the profile names, one per line. */
        bool $profiles = false,
        /* Only validate the configuration, don't print anything. */
        bool $quiet = false,
        /* Pin image tags to digests. */
        bool $resolveImageDigests = false,
        /* Print the volume names, one per line. */
        bool $volumes = false,
    ): Process {
        $this->docker->add('config');
        $this->docker->addIf($dryRun, '--dry-run');
        $this->docker->addIf($format, '--format', $format);
        $this->docker->addIf($hash, '--hash', $hash);
        $this->docker->addIf($images, '--images');
        $this->docker->addIf($noConsistency, '--no-consistency');
        $this->docker->addIf($noInterpolate, '--no-interpolate');
        $this->docker->addIf($noNormalize, '--no-normalize');
        $this->docker->addIf($noPathResolution, '--no-path-resolution');
        $this->docker->addIf($output, '--output', $output);
        $this->docker->addIf($profiles, '--profiles');
        $this->docker->addIf($quiet, '--quiet');
        $this->docker->addIf($resolveImageDigests, '--resolve-image-digests');
        $this->docker->addIf($services, '--services');
        $this->docker->addIf($volumes, '--volumes');
        $this->docker->addIf($services, null, $services);

        return $this->docker->runCommand();
    }

    /**
     * Usage:  docker compose cp [OPTIONS] SERVICE:SRC_PATH DEST_PATH|-     docker compose cp [OPTIONS] SRC_PATH|- SERVICE:DEST_PATH.
     */
    public function cp(
        string $service,
        string $src,
        string $path,
        string $dest,
        /* Archive mode (copy all uid/gid information) */
        bool $archive = false,
        /* Execute command in dry run mode -L, --follow-link Always follow symbol link in SRC_PATH */
        bool $dryRun = false,
        /* index of the container if service has multiple replicas */
        false|int $index = false,
    ): Process {
        $this->docker->add('cp');
        $this->docker->addIf($archive, '--archive');
        $this->docker->addIf($dryRun, '--dry-run');
        $this->docker->addIf($index, '--index', $index);
        $this->docker->addIf($service, null, $service);
        $this->docker->addIf($src, null, $src);
        $this->docker->addIf($path, null, $path);
        $this->docker->addIf($dest, null, $dest);
        $this->docker->addIf($path, null, $path);
        $this->docker->addIf($src, null, $src);
        $this->docker->addIf($path, null, $path);
        $this->docker->addIf($service, null, $service);
        $this->docker->addIf($dest, null, $dest);
        $this->docker->addIf($path, null, $path);

        return $this->docker->runCommand();
    }

    /**
     * Usage:  docker compose create [OPTIONS] [SERVICE...].
     */
    public function create(
        array $services = [],
        /* Build images before starting containers. */
        bool $build = false,
        /* Execute command in dry run mode */
        bool $dryRun = false,
        /* Recreate containers even if their configuration and image haven't changed. */
        bool $forceRecreate = false,
        /* Don't build an image, even if it's policy. */
        bool $noBuild = false,
        /* If containers already exist, don't recreate them. Incompatible with --force-recreate. */
        bool $noRecreate = false,
        /* Pull image before running ("always"|"missing"|"never"|"build") (default "policy") */
        false|string $pull = false,
        /* Remove containers for services not defined in the Compose file. */
        bool $removeOrphans = false,
        /* scale Scale SERVICE to NUM instances. Overrides the scale setting in the Compose file if present. */
        bool $scale = false,
    ): Process {
        $this->docker->add('create');
        $this->docker->addIf($build, '--build');
        $this->docker->addIf($dryRun, '--dry-run');
        $this->docker->addIf($forceRecreate, '--force-recreate');
        $this->docker->addIf($noBuild, '--no-build');
        $this->docker->addIf($noRecreate, '--no-recreate');
        $this->docker->addIf($pull, '--pull', $pull);
        $this->docker->addIf($removeOrphans, '--remove-orphans');
        $this->docker->addIf($scale, '--scale');
        $this->docker->addIf($services, null, $services);

        return $this->docker->runCommand();
    }

    /**
     * Usage:  docker compose down [OPTIONS] [SERVICES].
     */
    public function down(
        array $services = [],
        /* Execute command in dry run mode */
        bool $dryRun = false,
        /* Remove containers for services not defined in the Compose file. */
        bool $removeOrphans = false,
        /* Remove images used by services. "local" remove only images that don't have a custom tag ("local"|"all") */
        false|string $rmi = false,
        /* Specify a shutdown timeout in seconds */
        false|int $timeout = false,
        /* Remove named volumes declared in the "volumes" section of the Compose file and anonymous volumes attached to containers. */
        bool $volumes = false,
    ): Process {
        $this->docker->add('down');
        $this->docker->addIf($dryRun, '--dry-run');
        $this->docker->addIf($removeOrphans, '--remove-orphans');
        $this->docker->addIf($rmi, '--rmi', $rmi);
        $this->docker->addIf($timeout, '--timeout', $timeout);
        $this->docker->addIf($volumes, '--volumes');
        $this->docker->addIf($services, null, $services);

        return $this->docker->runCommand();
    }

    /**
     * Usage:  docker compose events [OPTIONS] [SERVICE...].
     */
    public function events(
        array $services = [],
        /* Execute command in dry run mode */
        bool $dryRun = false,
        /* Output events as a stream of json objects */
        bool $json = false,
    ): Process {
        $this->docker->add('events');
        $this->docker->addIf($dryRun, '--dry-run');
        $this->docker->addIf($json, '--json');
        $this->docker->addIf($services, null, $services);

        return $this->docker->runCommand();
    }

    /**
     * Usage:  docker compose exec [OPTIONS] SERVICE COMMAND [ARGS...].
     */
    public function exec(
        string $service,
        array $args = [],
        /* Detached mode: Run command in the background. */
        bool $detach = false,
        /* Execute command in dry run mode */
        bool $dryRun = false,
        /* Set environment variables */
        false|array $env = false,
        /* index of the container if service has multiple replicas -T, --no-TTY docker compose exec Disable pseudo-TTY allocation. By default docker compose exec allocates a TTY. (default true) */
        false|int $index = false,
        /* Give extended privileges to the process. */
        bool $privileged = false,
        /* Run the command as this user. */
        false|string $user = false,
        /* Path to workdir directory for this command. */
        false|string $workdir = false,
    ): Process {
        $this->docker->add('exec');
        $this->docker->addIf($detach, '--detach');
        $this->docker->addIf($dryRun, '--dry-run');
        $this->docker->addIf($env, '--env', $env);
        $this->docker->addIf($index, '--index', $index);
        $this->docker->addIf($privileged, '--privileged');
        $this->docker->addIf($user, '--user', $user);
        $this->docker->addIf($workdir, '--workdir', $workdir);
        $this->docker->addIf($service, null, $service);
        $this->docker->addIf($args, null, $args);

        return $this->docker->runCommand();
    }

    /**
     * Usage:  docker compose images [OPTIONS] [SERVICE...].
     */
    public function images(
        array $services = [],
        /* Execute command in dry run mode */
        bool $dryRun = false,
        /* Format the output. Values: [table | json]. (default "table") */
        false|string $format = false,
        /* Only display IDs */
        bool $quiet = false,
    ): Process {
        $this->docker->add('images');
        $this->docker->addIf($dryRun, '--dry-run');
        $this->docker->addIf($format, '--format', $format);
        $this->docker->addIf($quiet, '--quiet');
        $this->docker->addIf($services, null, $services);

        return $this->docker->runCommand();
    }

    /**
     * Usage:  docker compose kill [OPTIONS] [SERVICE...].
     */
    public function kill(
        array $services = [],
        /* Execute command in dry run mode */
        bool $dryRun = false,
        /* Remove containers for services not defined in the Compose file. */
        bool $removeOrphans = false,
        /* SIGNAL to send to the container. (default "SIGKILL") */
        false|string $signal = false,
    ): Process {
        $this->docker->add('kill');
        $this->docker->addIf($dryRun, '--dry-run');
        $this->docker->addIf($removeOrphans, '--remove-orphans');
        $this->docker->addIf($signal, '--signal', $signal);
        $this->docker->addIf($services, null, $services);

        return $this->docker->runCommand();
    }

    /**
     * Usage:  docker compose logs [OPTIONS] [SERVICE...].
     */
    public function logs(
        array $services = [],
        /* Execute command in dry run mode */
        bool $dryRun = false,
        /* Follow log output. */
        bool $follow = false,
        /* index of the container if service has multiple replicas */
        false|int $index = false,
        /* Produce monochrome output. */
        bool $noColor = false,
        /* Don't print prefix in logs. */
        bool $noLogPrefix = false,
        /* Show logs since timestamp (e.g. 2013-01-02T13:23:37Z) or relative (e.g. 42m for 42 minutes) */
        false|string $since = false,
        /* Number of lines to show from the end of the logs for each container. (default "all") */
        false|string $tail = false,
        /* Show timestamps. */
        bool $timestamps = false,
        /* Show logs before a timestamp (e.g. 2013-01-02T13:23:37Z) or relative (e.g. 42m for 42 minutes) */
        false|string $until = false,
    ): Process {
        $this->docker->add('logs');
        $this->docker->addIf($dryRun, '--dry-run');
        $this->docker->addIf($follow, '--follow');
        $this->docker->addIf($index, '--index', $index);
        $this->docker->addIf($noColor, '--no-color');
        $this->docker->addIf($noLogPrefix, '--no-log-prefix');
        $this->docker->addIf($since, '--since', $since);
        $this->docker->addIf($tail, '--tail', $tail);
        $this->docker->addIf($timestamps, '--timestamps');
        $this->docker->addIf($until, '--until', $until);
        $this->docker->addIf($services, null, $services);

        return $this->docker->runCommand();
    }

    /**
     * Usage:  docker compose ls [OPTIONS].
     */
    public function ls(
        /* Show all stopped Compose projects */
        bool $all = false,
        /* Execute command in dry run mode */
        bool $dryRun = false,
        /* Filter output based on conditions provided. */
        false|array $filter = false,
        /* Format the output. Values: [table | json]. (default "table") */
        false|string $format = false,
        /* Only display IDs. */
        bool $quiet = false,
    ): Process {
        $this->docker->add('ls');
        $this->docker->addIf($all, '--all');
        $this->docker->addIf($dryRun, '--dry-run');
        $this->docker->addIf($filter, '--filter', $filter);
        $this->docker->addIf($format, '--format', $format);
        $this->docker->addIf($quiet, '--quiet');

        return $this->docker->runCommand();
    }

    /**
     * Usage:  docker compose pause [SERVICE...].
     */
    public function pause(
        array $services = [],
        /* Execute command in dry run mode */
        bool $dryRun = false,
    ): Process {
        $this->docker->add('pause');
        $this->docker->addIf($dryRun, '--dry-run');
        $this->docker->addIf($services, null, $services);

        return $this->docker->runCommand();
    }

    /**
     * Usage:  docker compose port [OPTIONS] SERVICE PRIVATE_PORT.
     */
    public function port(
        string $service,
        string $private,
        string $port,
        /* Execute command in dry run mode */
        bool $dryRun = false,
        /* index of the container if service has multiple replicas */
        false|int $index = false,
        /* tcp or udp (default "tcp") */
        false|string $protocol = false,
    ): Process {
        $this->docker->add('port');
        $this->docker->addIf($dryRun, '--dry-run');
        $this->docker->addIf($index, '--index', $index);
        $this->docker->addIf($protocol, '--protocol', $protocol);
        $this->docker->addIf($service, null, $service);
        $this->docker->addIf($private, null, $private);
        $this->docker->addIf($port, null, $port);

        return $this->docker->runCommand();
    }

    /**
     * Usage:  docker compose ps [OPTIONS] [SERVICE...].
     */
    public function ps(
        /* Display services */
        bool $services = false,
        /* Show all stopped containers (including those created by the run command) */
        bool $all = false,
        /* Execute command in dry run mode */
        bool $dryRun = false,
        /* Filter services by a property (supported filters: status). */
        false|string $filter = false,
        /* Format output using a custom template: 'table': Print output in table format with column headers (default) 'table TEMPLATE': Print output in table format using the given Go template 'json': Print in JSON format 'TEMPLATE': Print output using the given Go template. Refer to https://docs.docker.com/go/formatting/ for more information about formatting output with templates (default "table") */
        false|string $format = false,
        /* Don't truncate output */
        bool $noTrunc = false,
        /* Include orphaned services (not declared by project) (default true) */
        bool $orphans = false,
        /* Only display IDs */
        bool $quiet = false,
        /* Filter services by status. Values: [paused | restarting | removing | running | dead | created | exited] */
        false|array $status = false,
    ): Process {
        $this->docker->add('ps');
        $this->docker->addIf($all, '--all');
        $this->docker->addIf($dryRun, '--dry-run');
        $this->docker->addIf($filter, '--filter', $filter);
        $this->docker->addIf($format, '--format', $format);
        $this->docker->addIf($noTrunc, '--no-trunc');
        $this->docker->addIf($orphans, '--orphans');
        $this->docker->addIf($quiet, '--quiet');
        $this->docker->addIf($services, '--services');
        $this->docker->addIf($status, '--status', $status);
        $this->docker->addIf($services, null, $services);

        return $this->docker->runCommand();
    }

    /**
     * Usage:  docker compose pull [OPTIONS] [SERVICE...].
     */
    public function pull(
        array $services = [],
        /* Execute command in dry run mode */
        bool $dryRun = false,
        /* Ignore images that can be built. */
        bool $ignoreBuildable = false,
        /* Pull what it can and ignores images with pull failures. */
        bool $ignorePullFailures = false,
        /* Also pull services declared as dependencies. */
        bool $includeDeps = false,
        /* Apply pull policy ("missing"|"always"). */
        false|string $policy = false,
        /* Pull without printing progress information. */
        bool $quiet = false,
    ): Process {
        $this->docker->add('pull');
        $this->docker->addIf($dryRun, '--dry-run');
        $this->docker->addIf($ignoreBuildable, '--ignore-buildable');
        $this->docker->addIf($ignorePullFailures, '--ignore-pull-failures');
        $this->docker->addIf($includeDeps, '--include-deps');
        $this->docker->addIf($policy, '--policy', $policy);
        $this->docker->addIf($quiet, '--quiet');
        $this->docker->addIf($services, null, $services);

        return $this->docker->runCommand();
    }

    /**
     * Usage:  docker compose push [OPTIONS] [SERVICE...].
     */
    public function push(
        array $services = [],
        /* Execute command in dry run mode */
        bool $dryRun = false,
        /* Push what it can and ignores images with push failures */
        bool $ignorePushFailures = false,
        /* Also push images of services declared as dependencies */
        bool $includeDeps = false,
        /* Push without printing progress information */
        bool $quiet = false,
    ): Process {
        $this->docker->add('push');
        $this->docker->addIf($dryRun, '--dry-run');
        $this->docker->addIf($ignorePushFailures, '--ignore-push-failures');
        $this->docker->addIf($includeDeps, '--include-deps');
        $this->docker->addIf($quiet, '--quiet');
        $this->docker->addIf($services, null, $services);

        return $this->docker->runCommand();
    }

    /**
     * Usage:  docker compose restart [OPTIONS] [SERVICE...].
     */
    public function restart(
        array $services = [],
        /* Execute command in dry run mode */
        bool $dryRun = false,
        /* Don't restart dependent services. */
        bool $noDeps = false,
        /* Specify a shutdown timeout in seconds */
        false|int $timeout = false,
    ): Process {
        $this->docker->add('restart');
        $this->docker->addIf($dryRun, '--dry-run');
        $this->docker->addIf($noDeps, '--no-deps');
        $this->docker->addIf($timeout, '--timeout', $timeout);
        $this->docker->addIf($services, null, $services);

        return $this->docker->runCommand();
    }

    /**
     * Usage:  docker compose rm [OPTIONS] [SERVICE...].
     */
    public function rm(
        array $services = [],
        /* Execute command in dry run mode */
        bool $dryRun = false,
        /* Don't ask to confirm removal */
        bool $force = false,
        /* Stop the containers, if required, before removing */
        bool $stop = false,
        /* Remove any anonymous volumes attached to containers */
        bool $volumes = false,
    ): Process {
        $this->docker->add('rm');
        $this->docker->addIf($dryRun, '--dry-run');
        $this->docker->addIf($force, '--force');
        $this->docker->addIf($stop, '--stop');
        $this->docker->addIf($volumes, '--volumes');
        $this->docker->addIf($services, null, $services);

        return $this->docker->runCommand();
    }

    /**
     * Usage:  docker compose run [OPTIONS] SERVICE [COMMAND] [ARGS...].
     */
    public function run(
        string $service,
        array $args = [],
        /* Build image before starting container. */
        bool $build = false,
        /* Add Linux capabilities */
        false|array $capAdd = false,
        /* Drop Linux capabilities */
        false|array $capDrop = false,
        /* Run container in background and print container ID */
        bool $detach = false,
        /* Execute command in dry run mode */
        bool $dryRun = false,
        /* Override the entrypoint of the image */
        false|string $entrypoint = false,
        /* Set environment variables */
        false|array $env = false,
        /* Keep STDIN open even if not attached. (default true) */
        bool $interactive = false,
        /* Add or override a label */
        false|array $label = false,
        /* Assign a name to the container -T, --no-TTY Disable pseudo-TTY allocation (default: auto-detected). (default true) */
        false|string $name = false,
        /* Don't start linked services. */
        bool $noDeps = false,
        /* Publish a container's port(s) to the host. */
        false|array $publish = false,
        /* Pull without printing progress information. */
        bool $quietPull = false,
        /* Remove containers for services not defined in the Compose file. */
        bool $removeOrphans = false,
        /* Automatically remove the container when it exits -P, --service-ports Run command with all service's ports enabled and mapped to the host. */
        bool $rm = false,
        /* Use the service's network useAliases in the network(s) the container connects to. */
        bool $useAliases = false,
        /* Run as specified username or uid */
        false|string $user = false,
        /* Bind mount a volume. */
        false|array $volume = false,
        /* Working directory inside the container */
        false|string $workdir = false,
    ): Process {
        $this->docker->add('run');
        $this->docker->addIf($build, '--build');
        $this->docker->addIf($capAdd, '--cap-add', $capAdd);
        $this->docker->addIf($capDrop, '--cap-drop', $capDrop);
        $this->docker->addIf($detach, '--detach');
        $this->docker->addIf($dryRun, '--dry-run');
        $this->docker->addIf($entrypoint, '--entrypoint', $entrypoint);
        $this->docker->addIf($env, '--env', $env);
        $this->docker->addIf($interactive, '--interactive');
        $this->docker->addIf($label, '--label', $label);
        $this->docker->addIf($name, '--name', $name);
        $this->docker->addIf($noDeps, '--no-deps');
        $this->docker->addIf($publish, '--publish', $publish);
        $this->docker->addIf($quietPull, '--quiet-pull');
        $this->docker->addIf($removeOrphans, '--remove-orphans');
        $this->docker->addIf($rm, '--rm');
        $this->docker->addIf($useAliases, '--use-aliases');
        $this->docker->addIf($user, '--user', $user);
        $this->docker->addIf($volume, '--volume', $volume);
        $this->docker->addIf($workdir, '--workdir', $workdir);
        $this->docker->addIf($service, null, $service);
        $this->docker->addIf($args, null, $args);

        return $this->docker->runCommand();
    }

    /**
     * Usage:  docker compose scale [SERVICE=REPLICAS...].
     */
    public function scale(
        string $service,
        array $replicas = [],
        /* Execute command in dry run mode */
        bool $dryRun = false,
        /* Don't start linked services. */
        bool $noDeps = false,
    ): Process {
        $this->docker->add('scale');
        $this->docker->addIf($dryRun, '--dry-run');
        $this->docker->addIf($noDeps, '--no-deps');
        $this->docker->addIf($service, null, $service);
        $this->docker->addIf($replicas, null, $replicas);

        return $this->docker->runCommand();
    }

    /**
     * Usage:  docker compose start [SERVICE...].
     */
    public function start(
        array $services = [],
        /* Execute command in dry run mode */
        bool $dryRun = false,
    ): Process {
        $this->docker->add('start');
        $this->docker->addIf($dryRun, '--dry-run');
        $this->docker->addIf($services, null, $services);

        return $this->docker->runCommand();
    }

    /**
     * Usage:  docker compose stats [OPTIONS] [SERVICE].
     */
    public function stats(
        string $service,
        /* Show all containers (default shows just running) */
        bool $all = false,
        /* Execute command in dry run mode */
        bool $dryRun = false,
        /* Format output using a custom template: 'table': Print output in table format with column headers (default) 'table TEMPLATE': Print output in table format using the given Go template 'json': Print in JSON format 'TEMPLATE': Print output using the given Go template. Refer to https://docs.docker.com/go/formatting/ for more information about formatting output with templates */
        false|string $format = false,
        /* Disable streaming stats and only pull the first result */
        bool $noStream = false,
        /* Do not truncate output */
        bool $noTrunc = false,
    ): Process {
        $this->docker->add('stats');
        $this->docker->addIf($all, '--all');
        $this->docker->addIf($dryRun, '--dry-run');
        $this->docker->addIf($format, '--format', $format);
        $this->docker->addIf($noStream, '--no-stream');
        $this->docker->addIf($noTrunc, '--no-trunc');
        $this->docker->addIf($service, null, $service);

        return $this->docker->runCommand();
    }

    /**
     * Usage:  docker compose stop [OPTIONS] [SERVICE...].
     */
    public function stop(
        array $services = [],
        /* Execute command in dry run mode */
        bool $dryRun = false,
        /* Specify a shutdown timeout in seconds */
        false|int $timeout = false,
    ): Process {
        $this->docker->add('stop');
        $this->docker->addIf($dryRun, '--dry-run');
        $this->docker->addIf($timeout, '--timeout', $timeout);
        $this->docker->addIf($services, null, $services);

        return $this->docker->runCommand();
    }

    /**
     * Usage:  docker compose top [SERVICES...].
     */
    public function top(
        array $services = [],
        /* Execute command in dry run mode */
        bool $dryRun = false,
    ): Process {
        $this->docker->add('top');
        $this->docker->addIf($dryRun, '--dry-run');
        $this->docker->addIf($services, null, $services);

        return $this->docker->runCommand();
    }

    /**
     * Usage:  docker compose unpause [SERVICE...].
     */
    public function unpause(
        array $services = [],
        /* Execute command in dry run mode */
        bool $dryRun = false,
    ): Process {
        $this->docker->add('unpause');
        $this->docker->addIf($dryRun, '--dry-run');
        $this->docker->addIf($services, null, $services);

        return $this->docker->runCommand();
    }

    /**
     * Usage:  docker compose up [OPTIONS] [SERVICE...].
     */
    public function up(
        array $services = [],
        /* Stops all containers if any container was stopped. Incompatible with -d */
        bool $abortOnContainerExit = false,
        /* Recreate dependent containers. Incompatible with --no-recreate. */
        bool $alwaysRecreateDeps = false,
        /* Restrict attaching to the specified services. Incompatible with */
        false|array $attach = false,
        /* Automatically attach to log output of dependent services. */
        bool $attachDependencies = false,
        /* Build images before starting containers. */
        bool $build = false,
        /* Detached mode: Run containers in the background */
        bool $detach = false,
        /* Execute command in dry run mode */
        bool $dryRun = false,
        /* Return the exit code of the selected service container. Implies */
        false|string $exitCodeFrom = false,
        /* Recreate containers even if their configuration and image haven't changed. */
        bool $forceRecreate = false,
        /* Do not attach (stream logs) to the specified services. */
        false|array $noAttach = false,
        /* Don't build an image, even if it's policy. */
        bool $noBuild = false,
        /* Produce monochrome output. */
        bool $noColor = false,
        /* Don't start linked services. */
        bool $noDeps = false,
        /* Don't print prefix in logs. */
        bool $noLogPrefix = false,
        /* If containers already exist, don't recreate them. Incompatible with */
        bool $noRecreate = false,
        /* Don't start the services after creating them. */
        bool $noStart = false,
        /* Pull image before running ("always"|"missing"|"never") (default "policy") */
        false|string $pull = false,
        /* Pull without printing progress information. */
        bool $quietPull = false,
        /* Remove containers for services not defined in the Compose file. -V, --renew-anon-volumes Recreate anonymous volumes instead of retrieving data from the previous containers. */
        bool $removeOrphans = false,
        /* scale Scale SERVICE to NUM instances. Overrides the scale setting in the Compose file if present. */
        bool $scale = false,
        /* Use this timeout in seconds for container shutdown when attached or when containers are already running. */
        false|int $timeout = false,
        /* Show timestamps. */
        bool $timestamps = false,
        /* Wait for services to be running|healthy. Implies detached mode. */
        bool $wait = false,
        /* Maximum duration to wait for the project to be running|healthy. */
        false|int $waitTimeout = false,
    ): Process {
        $this->docker->add('up');
        $this->docker->addIf($abortOnContainerExit, '--abort-on-container-exit');
        $this->docker->addIf($alwaysRecreateDeps, '--always-recreate-deps');
        $this->docker->addIf($attach, '--attach', $attach);
        $this->docker->addIf($attachDependencies, '--attach-dependencies');
        $this->docker->addIf($build, '--build');
        $this->docker->addIf($detach, '--detach');
        $this->docker->addIf($dryRun, '--dry-run');
        $this->docker->addIf($exitCodeFrom, '--exit-code-from', $exitCodeFrom);
        $this->docker->addIf($forceRecreate, '--force-recreate');
        $this->docker->addIf($noAttach, '--no-attach', $noAttach);
        $this->docker->addIf($noBuild, '--no-build');
        $this->docker->addIf($noColor, '--no-color');
        $this->docker->addIf($noDeps, '--no-deps');
        $this->docker->addIf($noLogPrefix, '--no-log-prefix');
        $this->docker->addIf($noRecreate, '--no-recreate');
        $this->docker->addIf($noStart, '--no-start');
        $this->docker->addIf($pull, '--pull', $pull);
        $this->docker->addIf($quietPull, '--quiet-pull');
        $this->docker->addIf($removeOrphans, '--remove-orphans');
        $this->docker->addIf($scale, '--scale');
        $this->docker->addIf($timeout, '--timeout', $timeout);
        $this->docker->addIf($timestamps, '--timestamps');
        $this->docker->addIf($wait, '--wait');
        $this->docker->addIf($waitTimeout, '--wait-timeout', $waitTimeout);
        $this->docker->addIf($services, null, $services);

        return $this->docker->runCommand();
    }

    /**
     * Usage:  docker compose version [OPTIONS].
     */
    public function version(
        /* Execute command in dry run mode */
        bool $dryRun = false,
        /* Format the output. Values: [pretty | json]. (Default: pretty) */
        false|string $format = false,
        /* Shows only Compose's version number. */
        bool $short = false,
    ): Process {
        $this->docker->add('version');
        $this->docker->addIf($dryRun, '--dry-run');
        $this->docker->addIf($format, '--format', $format);
        $this->docker->addIf($short, '--short');

        return $this->docker->runCommand();
    }

    /**
     * Usage:  docker compose wait SERVICE [SERVICE...] [OPTIONS].
     */
    public function wait(
        string $service,
        array $services = [],
        /* Drops project when the first container stops */
        bool $downProject = false,
        /* Execute command in dry run mode */
        bool $dryRun = false,
    ): Process {
        $this->docker->add('wait');
        $this->docker->addIf($downProject, '--down-project');
        $this->docker->addIf($dryRun, '--dry-run');
        $this->docker->addIf($service, null, $service);
        $this->docker->addIf($services, null, $services);

        return $this->docker->runCommand();
    }

    /**
     * Usage:  docker compose watch [SERVICE...].
     */
    public function watch(
        array $services = [],
        /* Execute command in dry run mode */
        bool $dryRun = false,
        /* Do not build & start services before watching */
        bool $noUp = false,
        /* hide build output */
        bool $quiet = false,
    ): Process {
        $this->docker->add('watch');
        $this->docker->addIf($dryRun, '--dry-run');
        $this->docker->addIf($noUp, '--no-up');
        $this->docker->addIf($quiet, '--quiet');
        $this->docker->addIf($services, null, $services);

        return $this->docker->runCommand();
    }
}

class DockerContainer
{
    public function __construct(
        private readonly Docker $docker,
    ) {
        $this->docker->add('container');
    }

    /**
     * Usage:  docker container attach [OPTIONS] CONTAINER.
     */
    public function attach(
        string $container,
        /* Override the key sequence for detaching a container */
        false|string $detachKeys = false,
        /* Do not attach STDIN */
        bool $noStdin = false,
        /* Proxy all received signals to the process (default true) */
        bool $sigProxy = false,
    ): Process {
        $this->docker->add('attach');
        $this->docker->addIf($detachKeys, '--detach-keys', $detachKeys);
        $this->docker->addIf($noStdin, '--no-stdin');
        $this->docker->addIf($sigProxy, '--sig-proxy');
        $this->docker->addIf($container, null, $container);

        return $this->docker->runCommand();
    }

    /**
     * Usage:  docker container commit [OPTIONS] CONTAINER [REPOSITORY[:TAG]].
     */
    public function commit(
        string $container,
        string $repository,
        string $tag,
        /* Author (e.g., "John Hannibal Smith <hannibal@a-team.com>") */
        false|string $author = false,
        /* Apply Dockerfile instruction to the created image */
        false|array $change = false,
        /* Commit message */
        false|string $message = false,
        /* Pause container during commit (default true) */
        bool $pause = false,
    ): Process {
        $this->docker->add('commit');
        $this->docker->addIf($author, '--author', $author);
        $this->docker->addIf($change, '--change', $change);
        $this->docker->addIf($message, '--message', $message);
        $this->docker->addIf($pause, '--pause');
        $this->docker->addIf($container, null, $container);
        $this->docker->addIf($repository, null, $repository);
        $this->docker->addIf($tag, null, $tag);

        return $this->docker->runCommand();
    }

    /**
     * Usage:  docker container cp [OPTIONS] CONTAINER:SRC_PATH DEST_PATH|-     docker cp [OPTIONS] SRC_PATH|- CONTAINER:DEST_PATH.
     */
    public function cp(
        string $container,
        string $src,
        string $path,
        string $dest,
        /* Archive mode (copy all uid/gid information) -L, --follow-link Always follow symbol link in SRC_PATH */
        bool $archive = false,
        /* Suppress progress output during copy. Progress output is automatically suppressed if no terminal is attached */
        bool $quiet = false,
    ): Process {
        $this->docker->add('cp');
        $this->docker->addIf($archive, '--archive');
        $this->docker->addIf($quiet, '--quiet');
        $this->docker->addIf($container, null, $container);
        $this->docker->addIf($src, null, $src);
        $this->docker->addIf($path, null, $path);
        $this->docker->addIf($dest, null, $dest);
        $this->docker->addIf($path, null, $path);
        $this->docker->addIf($src, null, $src);
        $this->docker->addIf($path, null, $path);
        $this->docker->addIf($container, null, $container);
        $this->docker->addIf($dest, null, $dest);
        $this->docker->addIf($path, null, $path);

        return $this->docker->runCommand();
    }

    /**
     * Usage:  docker container create [OPTIONS] IMAGE [COMMAND] [ARG...].
     */
    public function create(
        string $image,
        array $args = [],
        /* Add a custom host-to-IP mapping (host:ip) */
        false|array $addHost = false,
        /* Add an annotation to the container (passed through to the OCI runtime) (default map[]) */
        false|array $annotation = false,
        /* Attach to STDIN, STDOUT or STDERR */
        false|array $attach = false,
        /* Block IO (relative weight), between 10 and 1000, or 0 to disable (default 0) */
        false|int $blkioWeight = false,
        /* Block IO weight (relative device weight) (default []) */
        false|array $blkioWeightDevice = false,
        /* Add Linux capabilities */
        false|array $capAdd = false,
        /* Drop Linux capabilities */
        false|array $capDrop = false,
        /* Optional parent cgroup for the container */
        false|string $cgroupParent = false,
        /* Cgroup namespace to use (host|private) 'host': Run the container in the Docker host's cgroup namespace 'private': Run the container in its own private cgroup namespace '': Use the cgroup namespace as configured by the default-cgroupns-mode option on the daemon (default) */
        false|string $cgroupns = false,
        /* Write the container ID to the file */
        false|string $cidfile = false,
        /* Limit CPU CFS (Completely Fair Scheduler) period */
        false|int $cpuPeriod = false,
        /* Limit CPU CFS (Completely Fair Scheduler) quota */
        false|int $cpuQuota = false,
        /* Limit CPU real-time period in microseconds */
        false|int $cpuRtPeriod = false,
        /* Limit CPU real-time runtime in microseconds */
        false|int $cpuRtRuntime = false,
        /* CPU shares (relative weight) */
        false|int $cpuShares = false,
        /* Number of CPUs */
        false|int $cpus = false,
        /* CPUs in which to allow execution (0-3, 0,1) */
        false|string $cpusetCpus = false,
        /* MEMs in which to allow execution (0-3, 0,1) */
        false|string $cpusetMems = false,
        /* Add a host device to the container */
        false|array $device = false,
        /* Add a rule to the cgroup allowed devices list */
        false|array $deviceCgroupRule = false,
        /* Limit read rate (bytes per second) from a device (default []) */
        false|array $deviceReadBps = false,
        /* Limit read rate (IO per second) from a device (default []) */
        false|array $deviceReadIops = false,
        /* Limit write rate (bytes per second) to a device (default []) */
        false|array $deviceWriteBps = false,
        /* Limit write rate (IO per second) to a device (default []) */
        false|array $deviceWriteIops = false,
        /* Skip image verification (default true) */
        bool $disableContentTrust = false,
        /* Set custom DNS servers */
        false|array $dns = false,
        /* Set DNS options */
        false|array $dnsOption = false,
        /* Set custom DNS search domains */
        false|array $dnsSearch = false,
        /* Container NIS domain name */
        false|string $domainname = false,
        /* Overwrite the default ENTRYPOINT of the image */
        false|string $entrypoint = false,
        /* Set environment variables */
        false|array $env = false,
        /* Read in a file of environment variables */
        false|array $envFile = false,
        /* Expose a port or a range of ports */
        false|array $expose = false,
        /* gpu-request GPU devices to add to the container ('all' to pass all GPUs) */
        bool $gpus = false,
        /* Add additional groups to join */
        false|array $groupAdd = false,
        /* Command to run to check health */
        false|string $healthCmd = false,
        /* Time between running the check (ms|s|m|h) (default 0s) */
        false|string $healthInterval = false,
        /* Consecutive failures needed to report unhealthy */
        false|int $healthRetries = false,
        /* Time between running the check during the start period (ms|s|m|h) (default 0s) */
        false|string $healthStartInterval = false,
        /* Start period for the container to initialize before starting health-retries countdown (ms|s|m|h) (default 0s) */
        false|string $healthStartPeriod = false,
        /* Maximum time to allow one check to run (ms|s|m|h) (default 0s) */
        false|string $healthTimeout = false,
        /* Print usage */
        bool $help = false,
        /* Container host name */
        false|string $hostname = false,
        /* Run an init inside the container that forwards signals and reaps processes */
        bool $init = false,
        /* Keep STDIN open even if not attached */
        bool $interactive = false,
        /* IPv4 address (e.g., 172.30.100.104) */
        false|string $ip = false,
        /* IPv6 address (e.g., 2001:db8::33) */
        false|string $ip6 = false,
        /* IPC mode to use */
        false|string $ipc = false,
        /* Container isolation technology */
        false|string $isolation = false,
        /* Kernel memory limit */
        false|int $kernelMemory = false,
        /* Set meta data on a container */
        false|array $label = false,
        /* Read in a line delimited file of labels */
        false|array $labelFile = false,
        /* Add link to another container */
        false|array $link = false,
        /* Container IPv4/IPv6 link-local addresses */
        false|array $linkLocalIp = false,
        /* Logging driver for the container */
        false|string $logDriver = false,
        /* Log driver options */
        false|array $logOpt = false,
        /* Container MAC address (e.g., 92:d0:c6:0a:29:33) */
        false|string $macAddress = false,
        /* Memory limit */
        false|int $memory = false,
        /* Memory soft limit */
        false|int $memoryReservation = false,
        /* Swap limit equal to memory plus swap: '-1' to enable unlimited swap */
        false|int $memorySwap = false,
        /* Tune container memory swappiness (0 to 100) (default -1) */
        false|int $memorySwappiness = false,
        /* mount Attach a filesystem mount to the container */
        bool $mount = false,
        /* Assign a name to the container */
        false|string $name = false,
        /* network Connect a container to a network */
        bool $network = false,
        /* Add network-scoped alias for the container */
        false|array $networkAlias = false,
        /* Disable any container-specified HEALTHCHECK */
        bool $noHealthcheck = false,
        /* Disable OOM Killer */
        bool $oomKillDisable = false,
        /* Tune host's OOM preferences (-1000 to 1000) */
        false|int $oomScoreAdj = false,
        /* PID namespace to use */
        false|string $pid = false,
        /* Tune container pids limit (set -1 for unlimited) */
        false|int $pidsLimit = false,
        /* Set platform if server is multi-platform capable */
        false|string $platform = false,
        /* Give extended privileges to this container */
        bool $privileged = false,
        /* Publish a container's port(s) to the host -P, --publish-all Publish all exposed ports to random ports */
        false|array $publish = false,
        /* Pull image before creating ("always", "|missing", "never") (default "missing") */
        false|string $pull = false,
        /* Suppress the pull output */
        bool $quiet = false,
        /* Mount the container's root filesystem as read only */
        bool $readOnly = false,
        /* Restart policy to apply when a container exits (default "no") */
        false|string $restart = false,
        /* Automatically remove the container when it exits */
        bool $rm = false,
        /* Runtime to use for this container */
        false|string $runtime = false,
        /* Security Options */
        false|array $securityOpt = false,
        /* Size of /dev/shm */
        false|int $shmSize = false,
        /* Signal to stop the container */
        false|string $stopSignal = false,
        /* Timeout (in seconds) to stop a container */
        false|int $stopTimeout = false,
        /* Storage driver options for the container */
        false|array $storageOpt = false,
        /* Sysctl options (default map[]) */
        false|array $sysctl = false,
        /* Mount a tmpfs directory */
        false|array $tmpfs = false,
        /* Allocate a pseudo-TTY */
        bool $tty = false,
        /* Ulimit options (default []) */
        false|int $ulimit = false,
        /* Username or UID (format: <name|uid>[:<group|gid>]) */
        false|string $user = false,
        /* UserEntity namespace to use */
        false|string $userns = false,
        /* UTS namespace to use */
        false|string $uts = false,
        /* Bind mount a volume */
        false|array $volume = false,
        /* Optional volume driver for the container */
        false|string $volumeDriver = false,
        /* Mount volumes from the specified container(s) */
        false|array $volumesFrom = false,
        /* Working directory inside the container */
        false|string $workdir = false,
    ): Process {
        $this->docker->add('create');
        $this->docker->addIf($addHost, '--add-host', $addHost);
        $this->docker->addIf($annotation, '--annotation', $annotation);
        $this->docker->addIf($attach, '--attach', $attach);
        $this->docker->addIf($blkioWeight, '--blkio-weight', $blkioWeight);
        $this->docker->addIf($blkioWeightDevice, '--blkio-weight-device', $blkioWeightDevice);
        $this->docker->addIf($capAdd, '--cap-add', $capAdd);
        $this->docker->addIf($capDrop, '--cap-drop', $capDrop);
        $this->docker->addIf($cgroupParent, '--cgroup-parent', $cgroupParent);
        $this->docker->addIf($cgroupns, '--cgroupns', $cgroupns);
        $this->docker->addIf($cidfile, '--cidfile', $cidfile);
        $this->docker->addIf($cpuPeriod, '--cpu-period', $cpuPeriod);
        $this->docker->addIf($cpuQuota, '--cpu-quota', $cpuQuota);
        $this->docker->addIf($cpuRtPeriod, '--cpu-rt-period', $cpuRtPeriod);
        $this->docker->addIf($cpuRtRuntime, '--cpu-rt-runtime', $cpuRtRuntime);
        $this->docker->addIf($cpuShares, '--cpu-shares', $cpuShares);
        $this->docker->addIf($cpus, '--cpus', $cpus);
        $this->docker->addIf($cpusetCpus, '--cpuset-cpus', $cpusetCpus);
        $this->docker->addIf($cpusetMems, '--cpuset-mems', $cpusetMems);
        $this->docker->addIf($device, '--device', $device);
        $this->docker->addIf($deviceCgroupRule, '--device-cgroup-rule', $deviceCgroupRule);
        $this->docker->addIf($deviceReadBps, '--device-read-bps', $deviceReadBps);
        $this->docker->addIf($deviceReadIops, '--device-read-iops', $deviceReadIops);
        $this->docker->addIf($deviceWriteBps, '--device-write-bps', $deviceWriteBps);
        $this->docker->addIf($deviceWriteIops, '--device-write-iops', $deviceWriteIops);
        $this->docker->addIf($disableContentTrust, '--disable-content-trust');
        $this->docker->addIf($dns, '--dns', $dns);
        $this->docker->addIf($dnsOption, '--dns-option', $dnsOption);
        $this->docker->addIf($dnsSearch, '--dns-search', $dnsSearch);
        $this->docker->addIf($domainname, '--domainname', $domainname);
        $this->docker->addIf($entrypoint, '--entrypoint', $entrypoint);
        $this->docker->addIf($env, '--env', $env);
        $this->docker->addIf($envFile, '--env-file', $envFile);
        $this->docker->addIf($expose, '--expose', $expose);
        $this->docker->addIf($gpus, '--gpus');
        $this->docker->addIf($groupAdd, '--group-add', $groupAdd);
        $this->docker->addIf($healthCmd, '--health-cmd', $healthCmd);
        $this->docker->addIf($healthInterval, '--health-interval', $healthInterval);
        $this->docker->addIf($healthRetries, '--health-retries', $healthRetries);
        $this->docker->addIf($healthStartInterval, '--health-start-interval', $healthStartInterval);
        $this->docker->addIf($healthStartPeriod, '--health-start-period', $healthStartPeriod);
        $this->docker->addIf($healthTimeout, '--health-timeout', $healthTimeout);
        $this->docker->addIf($help, '--help');
        $this->docker->addIf($hostname, '--hostname', $hostname);
        $this->docker->addIf($init, '--init');
        $this->docker->addIf($interactive, '--interactive');
        $this->docker->addIf($ip, '--ip', $ip);
        $this->docker->addIf($ip6, '--ip6', $ip6);
        $this->docker->addIf($ipc, '--ipc', $ipc);
        $this->docker->addIf($isolation, '--isolation', $isolation);
        $this->docker->addIf($kernelMemory, '--kernel-memory', $kernelMemory);
        $this->docker->addIf($label, '--label', $label);
        $this->docker->addIf($labelFile, '--label-file', $labelFile);
        $this->docker->addIf($link, '--link', $link);
        $this->docker->addIf($linkLocalIp, '--link-local-ip', $linkLocalIp);
        $this->docker->addIf($logDriver, '--log-driver', $logDriver);
        $this->docker->addIf($logOpt, '--log-opt', $logOpt);
        $this->docker->addIf($macAddress, '--mac-address', $macAddress);
        $this->docker->addIf($memory, '--memory', $memory);
        $this->docker->addIf($memoryReservation, '--memory-reservation', $memoryReservation);
        $this->docker->addIf($memorySwap, '--memory-swap', $memorySwap);
        $this->docker->addIf($memorySwappiness, '--memory-swappiness', $memorySwappiness);
        $this->docker->addIf($mount, '--mount');
        $this->docker->addIf($name, '--name', $name);
        $this->docker->addIf($network, '--network');
        $this->docker->addIf($networkAlias, '--network-alias', $networkAlias);
        $this->docker->addIf($noHealthcheck, '--no-healthcheck');
        $this->docker->addIf($oomKillDisable, '--oom-kill-disable');
        $this->docker->addIf($oomScoreAdj, '--oom-score-adj', $oomScoreAdj);
        $this->docker->addIf($pid, '--pid', $pid);
        $this->docker->addIf($pidsLimit, '--pids-limit', $pidsLimit);
        $this->docker->addIf($platform, '--platform', $platform);
        $this->docker->addIf($privileged, '--privileged');
        $this->docker->addIf($publish, '--publish', $publish);
        $this->docker->addIf($pull, '--pull', $pull);
        $this->docker->addIf($quiet, '--quiet');
        $this->docker->addIf($readOnly, '--read-only');
        $this->docker->addIf($restart, '--restart', $restart);
        $this->docker->addIf($rm, '--rm');
        $this->docker->addIf($runtime, '--runtime', $runtime);
        $this->docker->addIf($securityOpt, '--security-opt', $securityOpt);
        $this->docker->addIf($shmSize, '--shm-size', $shmSize);
        $this->docker->addIf($stopSignal, '--stop-signal', $stopSignal);
        $this->docker->addIf($stopTimeout, '--stop-timeout', $stopTimeout);
        $this->docker->addIf($storageOpt, '--storage-opt', $storageOpt);
        $this->docker->addIf($sysctl, '--sysctl', $sysctl);
        $this->docker->addIf($tmpfs, '--tmpfs', $tmpfs);
        $this->docker->addIf($tty, '--tty');
        $this->docker->addIf($ulimit, '--ulimit', $ulimit);
        $this->docker->addIf($user, '--user', $user);
        $this->docker->addIf($userns, '--userns', $userns);
        $this->docker->addIf($uts, '--uts', $uts);
        $this->docker->addIf($volume, '--volume', $volume);
        $this->docker->addIf($volumeDriver, '--volume-driver', $volumeDriver);
        $this->docker->addIf($volumesFrom, '--volumes-from', $volumesFrom);
        $this->docker->addIf($workdir, '--workdir', $workdir);
        $this->docker->addIf($image, null, $image);
        $this->docker->addIf($args, null, $args);

        return $this->docker->runCommand();
    }

    /**
     * Usage:  docker container diff CONTAINER.
     */
    public function diff(string $container): Process
    {
        $this->docker->add('diff');

        $this->docker->addIf($container, null, $container);

        return $this->docker->runCommand();
    }

    /**
     * Usage:  docker container exec [OPTIONS] CONTAINER COMMAND [ARG...].
     */
    public function exec(
        string $container,
        array $args = [],
        /* Detached mode: run command in the background */
        bool $detach = false,
        /* Override the key sequence for detaching a container */
        false|string $detachKeys = false,
        /* Set environment variables */
        false|array $env = false,
        /* Read in a file of environment variables */
        false|array $envFile = false,
        /* Keep STDIN open even if not attached */
        bool $interactive = false,
        /* Give extended privileges to the command */
        bool $privileged = false,
        /* Allocate a pseudo-TTY */
        bool $tty = false,
        /* Username or UID (format: "<name|uid>[:<group|gid>]") */
        false|string $user = false,
        /* Working directory inside the container */
        false|string $workdir = false,
    ): Process {
        $this->docker->add('exec');
        $this->docker->addIf($detach, '--detach');
        $this->docker->addIf($detachKeys, '--detach-keys', $detachKeys);
        $this->docker->addIf($env, '--env', $env);
        $this->docker->addIf($envFile, '--env-file', $envFile);
        $this->docker->addIf($interactive, '--interactive');
        $this->docker->addIf($privileged, '--privileged');
        $this->docker->addIf($tty, '--tty');
        $this->docker->addIf($user, '--user', $user);
        $this->docker->addIf($workdir, '--workdir', $workdir);
        $this->docker->addIf($container, null, $container);
        $this->docker->addIf($args, null, $args);

        return $this->docker->runCommand();
    }

    /**
     * Usage:  docker container export [OPTIONS] CONTAINER.
     */
    public function export(
        string $container,
        /* Write to a file, instead of STDOUT */
        false|string $output = false,
    ): Process {
        $this->docker->add('export');
        $this->docker->addIf($output, '--output', $output);
        $this->docker->addIf($container, null, $container);

        return $this->docker->runCommand();
    }

    /**
     * Usage:  docker container inspect [OPTIONS] CONTAINER [CONTAINER...].
     */
    public function inspect(
        string $container,
        array $containers = [],
        /* Format output using a custom template: 'json': Print in JSON format 'TEMPLATE': Print output using the given Go template. Refer to https://docs.docker.com/go/formatting/ for more information about formatting output with templates */
        false|string $format = false,
        /* Display total file sizes */
        bool $size = false,
    ): Process {
        $this->docker->add('inspect');
        $this->docker->addIf($format, '--format', $format);
        $this->docker->addIf($size, '--size');
        $this->docker->addIf($container, null, $container);
        $this->docker->addIf($containers, null, $containers);

        return $this->docker->runCommand();
    }

    /**
     * Usage:  docker container kill [OPTIONS] CONTAINER [CONTAINER...].
     */
    public function kill(
        string $container,
        array $containers = [],
        /* Signal to send to the container */
        false|string $signal = false,
    ): Process {
        $this->docker->add('kill');
        $this->docker->addIf($signal, '--signal', $signal);
        $this->docker->addIf($container, null, $container);
        $this->docker->addIf($containers, null, $containers);

        return $this->docker->runCommand();
    }

    /**
     * Usage:  docker container logs [OPTIONS] CONTAINER.
     */
    public function logs(
        string $container,
        /* Show extra details provided to logs */
        bool $details = false,
        /* Follow log output */
        bool $follow = false,
        /* Show logs since timestamp (e.g. "2013-01-02T13:23:37Z") or relative (e.g. "42m" for 42 minutes) */
        false|string $since = false,
        /* Number of lines to show from the end of the logs (default "all") */
        false|string $tail = false,
        /* Show timestamps */
        bool $timestamps = false,
        /* Show logs before a timestamp (e.g. "2013-01-02T13:23:37Z") or relative (e.g. "42m" for 42 minutes) */
        false|string $until = false,
    ): Process {
        $this->docker->add('logs');
        $this->docker->addIf($details, '--details');
        $this->docker->addIf($follow, '--follow');
        $this->docker->addIf($since, '--since', $since);
        $this->docker->addIf($tail, '--tail', $tail);
        $this->docker->addIf($timestamps, '--timestamps');
        $this->docker->addIf($until, '--until', $until);
        $this->docker->addIf($container, null, $container);

        return $this->docker->runCommand();
    }

    /**
     * Usage:  docker container ls [OPTIONS].
     */
    public function ls(
        /* Show all containers (default shows just running) */
        bool $all = false,
        /* Filter output based on conditions provided */
        false|array $filter = false,
        /* Format output using a custom template: 'table': Print output in table format with column headers (default) 'table TEMPLATE': Print output in table format using the given Go template 'json': Print in JSON format 'TEMPLATE': Print output using the given Go template. Refer to https://docs.docker.com/go/formatting/ for more information about formatting output with templates */
        false|string $format = false,
        /* Show n last created containers (includes all states) (default -1) */
        false|int $last = false,
        /* Show the latest created container (includes all states) */
        bool $latest = false,
        /* Don't truncate output */
        bool $noTrunc = false,
        /* Only display container IDs */
        bool $quiet = false,
        /* Display total file sizes */
        bool $size = false,
    ): Process {
        $this->docker->add('ls');
        $this->docker->addIf($all, '--all');
        $this->docker->addIf($filter, '--filter', $filter);
        $this->docker->addIf($format, '--format', $format);
        $this->docker->addIf($last, '--last', $last);
        $this->docker->addIf($latest, '--latest');
        $this->docker->addIf($noTrunc, '--no-trunc');
        $this->docker->addIf($quiet, '--quiet');
        $this->docker->addIf($size, '--size');

        return $this->docker->runCommand();
    }

    /**
     * Usage:  docker container pause CONTAINER [CONTAINER...].
     */
    public function pause(string $container, array $containers = []): Process
    {
        $this->docker->add('pause');

        $this->docker->addIf($container, null, $container);
        $this->docker->addIf($containers, null, $containers);

        return $this->docker->runCommand();
    }

    /**
     * Usage:  docker container port CONTAINER [PRIVATE_PORT[/PROTO]].
     */
    public function port(string $container, string $private, string $port, string $proto): Process
    {
        $this->docker->add('port');

        $this->docker->addIf($container, null, $container);
        $this->docker->addIf($private, null, $private);
        $this->docker->addIf($port, null, $port);
        $this->docker->addIf($proto, null, $proto);

        return $this->docker->runCommand();
    }

    /**
     * Usage:  docker container prune [OPTIONS].
     */
    public function prune(
        /* Provide filter values (e.g. "until=<timestamp>") */
        false|array $filter = false,
        /* Do not prompt for confirmation */
        bool $force = false,
    ): Process {
        $this->docker->add('prune');
        $this->docker->addIf($filter, '--filter', $filter);
        $this->docker->addIf($force, '--force');

        return $this->docker->runCommand();
    }

    /**
     * Usage:  docker container rename CONTAINER NEW_NAME.
     */
    public function rename(string $container, string $new, string $name): Process
    {
        $this->docker->add('rename');

        $this->docker->addIf($container, null, $container);
        $this->docker->addIf($new, null, $new);
        $this->docker->addIf($name, null, $name);

        return $this->docker->runCommand();
    }

    /**
     * Usage:  docker container restart [OPTIONS] CONTAINER [CONTAINER...].
     */
    public function restart(
        string $container,
        array $containers = [],
        /* Signal to send to the container */
        false|string $signal = false,
        /* Seconds to wait before killing the container */
        false|int $time = false,
    ): Process {
        $this->docker->add('restart');
        $this->docker->addIf($signal, '--signal', $signal);
        $this->docker->addIf($time, '--time', $time);
        $this->docker->addIf($container, null, $container);
        $this->docker->addIf($containers, null, $containers);

        return $this->docker->runCommand();
    }

    /**
     * Usage:  docker container rm [OPTIONS] CONTAINER [CONTAINER...].
     */
    public function rm(
        string $container,
        array $containers = [],
        /* Force the removal of a running container (uses SIGKILL) */
        bool $force = false,
        /* Remove the specified link */
        bool $link = false,
        /* Remove anonymous volumes associated with the container */
        bool $volumes = false,
    ): Process {
        $this->docker->add('rm');
        $this->docker->addIf($force, '--force');
        $this->docker->addIf($link, '--link');
        $this->docker->addIf($volumes, '--volumes');
        $this->docker->addIf($container, null, $container);
        $this->docker->addIf($containers, null, $containers);

        return $this->docker->runCommand();
    }

    /**
     * Usage:  docker container run [OPTIONS] IMAGE [COMMAND] [ARG...].
     */
    public function run(
        string $image,
        array $args = [],
        /* Add a custom host-to-IP mapping (host:ip) */
        false|array $addHost = false,
        /* Add an annotation to the container (passed through to the OCI runtime) (default map[]) */
        false|array $annotation = false,
        /* Attach to STDIN, STDOUT or STDERR */
        false|array $attach = false,
        /* Block IO (relative weight), between 10 and 1000, or 0 to disable (default 0) */
        false|int $blkioWeight = false,
        /* Block IO weight (relative device weight) (default []) */
        false|array $blkioWeightDevice = false,
        /* Add Linux capabilities */
        false|array $capAdd = false,
        /* Drop Linux capabilities */
        false|array $capDrop = false,
        /* Optional parent cgroup for the container */
        false|string $cgroupParent = false,
        /* Cgroup namespace to use (host|private) 'host': Run the container in the Docker host's cgroup namespace 'private': Run the container in its own private cgroup namespace '': Use the cgroup namespace as configured by the default-cgroupns-mode option on the daemon (default) */
        false|string $cgroupns = false,
        /* Write the container ID to the file */
        false|string $cidfile = false,
        /* Limit CPU CFS (Completely Fair Scheduler) period */
        false|int $cpuPeriod = false,
        /* Limit CPU CFS (Completely Fair Scheduler) quota */
        false|int $cpuQuota = false,
        /* Limit CPU real-time period in microseconds */
        false|int $cpuRtPeriod = false,
        /* Limit CPU real-time runtime in microseconds */
        false|int $cpuRtRuntime = false,
        /* CPU shares (relative weight) */
        false|int $cpuShares = false,
        /* Number of CPUs */
        false|int $cpus = false,
        /* CPUs in which to allow execution (0-3, 0,1) */
        false|string $cpusetCpus = false,
        /* MEMs in which to allow execution (0-3, 0,1) */
        false|string $cpusetMems = false,
        /* Run container in background and print container ID */
        bool $detach = false,
        /* Override the key sequence for detaching a container */
        false|string $detachKeys = false,
        /* Add a host device to the container */
        false|array $device = false,
        /* Add a rule to the cgroup allowed devices list */
        false|array $deviceCgroupRule = false,
        /* Limit read rate (bytes per second) from a device (default []) */
        false|array $deviceReadBps = false,
        /* Limit read rate (IO per second) from a device (default []) */
        false|array $deviceReadIops = false,
        /* Limit write rate (bytes per second) to a device (default []) */
        false|array $deviceWriteBps = false,
        /* Limit write rate (IO per second) to a device (default []) */
        false|array $deviceWriteIops = false,
        /* Skip image verification (default true) */
        bool $disableContentTrust = false,
        /* Set custom DNS servers */
        false|array $dns = false,
        /* Set DNS options */
        false|array $dnsOption = false,
        /* Set custom DNS search domains */
        false|array $dnsSearch = false,
        /* Container NIS domain name */
        false|string $domainname = false,
        /* Overwrite the default ENTRYPOINT of the image */
        false|string $entrypoint = false,
        /* Set environment variables */
        false|array $env = false,
        /* Read in a file of environment variables */
        false|array $envFile = false,
        /* Expose a port or a range of ports */
        false|array $expose = false,
        /* gpu-request GPU devices to add to the container ('all' to pass all GPUs) */
        bool $gpus = false,
        /* Add additional groups to join */
        false|array $groupAdd = false,
        /* Command to run to check health */
        false|string $healthCmd = false,
        /* Time between running the check (ms|s|m|h) (default 0s) */
        false|string $healthInterval = false,
        /* Consecutive failures needed to report unhealthy */
        false|int $healthRetries = false,
        /* Time between running the check during the start period (ms|s|m|h) (default 0s) */
        false|string $healthStartInterval = false,
        /* Start period for the container to initialize before starting health-retries countdown (ms|s|m|h) (default 0s) */
        false|string $healthStartPeriod = false,
        /* Maximum time to allow one check to run (ms|s|m|h) (default 0s) */
        false|string $healthTimeout = false,
        /* Print usage */
        bool $help = false,
        /* Container host name */
        false|string $hostname = false,
        /* Run an init inside the container that forwards signals and reaps processes */
        bool $init = false,
        /* Keep STDIN open even if not attached */
        bool $interactive = false,
        /* IPv4 address (e.g., 172.30.100.104) */
        false|string $ip = false,
        /* IPv6 address (e.g., 2001:db8::33) */
        false|string $ip6 = false,
        /* IPC mode to use */
        false|string $ipc = false,
        /* Container isolation technology */
        false|string $isolation = false,
        /* Kernel memory limit */
        false|int $kernelMemory = false,
        /* Set meta data on a container */
        false|array $label = false,
        /* Read in a line delimited file of labels */
        false|array $labelFile = false,
        /* Add link to another container */
        false|array $link = false,
        /* Container IPv4/IPv6 link-local addresses */
        false|array $linkLocalIp = false,
        /* Logging driver for the container */
        false|string $logDriver = false,
        /* Log driver options */
        false|array $logOpt = false,
        /* Container MAC address (e.g., 92:d0:c6:0a:29:33) */
        false|string $macAddress = false,
        /* Memory limit */
        false|int $memory = false,
        /* Memory soft limit */
        false|int $memoryReservation = false,
        /* Swap limit equal to memory plus swap: '-1' to enable unlimited swap */
        false|int $memorySwap = false,
        /* Tune container memory swappiness (0 to 100) (default -1) */
        false|int $memorySwappiness = false,
        /* mount Attach a filesystem mount to the container */
        bool $mount = false,
        /* Assign a name to the container */
        false|string $name = false,
        /* network Connect a container to a network */
        bool $network = false,
        /* Add network-scoped alias for the container */
        false|array $networkAlias = false,
        /* Disable any container-specified HEALTHCHECK */
        bool $noHealthcheck = false,
        /* Disable OOM Killer */
        bool $oomKillDisable = false,
        /* Tune host's OOM preferences (-1000 to 1000) */
        false|int $oomScoreAdj = false,
        /* PID namespace to use */
        false|string $pid = false,
        /* Tune container pids limit (set -1 for unlimited) */
        false|int $pidsLimit = false,
        /* Set platform if server is multi-platform capable */
        false|string $platform = false,
        /* Give extended privileges to this container */
        bool $privileged = false,
        /* Publish a container's port(s) to the host -P, --publish-all Publish all exposed ports to random ports */
        false|array $publish = false,
        /* Pull image before running ("always", "missing", "never") (default "missing") */
        false|string $pull = false,
        /* Suppress the pull output */
        bool $quiet = false,
        /* Mount the container's root filesystem as read only */
        bool $readOnly = false,
        /* Restart policy to apply when a container exits (default "no") */
        false|string $restart = false,
        /* Automatically remove the container when it exits */
        bool $rm = false,
        /* Runtime to use for this container */
        false|string $runtime = false,
        /* Security Options */
        false|array $securityOpt = false,
        /* Size of /dev/shm */
        false|int $shmSize = false,
        /* Proxy received signals to the process (default true) */
        bool $sigProxy = false,
        /* Signal to stop the container */
        false|string $stopSignal = false,
        /* Timeout (in seconds) to stop a container */
        false|int $stopTimeout = false,
        /* Storage driver options for the container */
        false|array $storageOpt = false,
        /* Sysctl options (default map[]) */
        false|array $sysctl = false,
        /* Mount a tmpfs directory */
        false|array $tmpfs = false,
        /* Allocate a pseudo-TTY */
        bool $tty = false,
        /* Ulimit options (default []) */
        false|int $ulimit = false,
        /* Username or UID (format: <name|uid>[:<group|gid>]) */
        false|string $user = false,
        /* UserEntity namespace to use */
        false|string $userns = false,
        /* UTS namespace to use */
        false|string $uts = false,
        /* Bind mount a volume */
        false|array $volume = false,
        /* Optional volume driver for the container */
        false|string $volumeDriver = false,
        /* Mount volumes from the specified container(s) */
        false|array $volumesFrom = false,
        /* Working directory inside the container */
        false|string $workdir = false,
    ): Process {
        $this->docker->add('run');
        $this->docker->addIf($addHost, '--add-host', $addHost);
        $this->docker->addIf($annotation, '--annotation', $annotation);
        $this->docker->addIf($attach, '--attach', $attach);
        $this->docker->addIf($blkioWeight, '--blkio-weight', $blkioWeight);
        $this->docker->addIf($blkioWeightDevice, '--blkio-weight-device', $blkioWeightDevice);
        $this->docker->addIf($capAdd, '--cap-add', $capAdd);
        $this->docker->addIf($capDrop, '--cap-drop', $capDrop);
        $this->docker->addIf($cgroupParent, '--cgroup-parent', $cgroupParent);
        $this->docker->addIf($cgroupns, '--cgroupns', $cgroupns);
        $this->docker->addIf($cidfile, '--cidfile', $cidfile);
        $this->docker->addIf($cpuPeriod, '--cpu-period', $cpuPeriod);
        $this->docker->addIf($cpuQuota, '--cpu-quota', $cpuQuota);
        $this->docker->addIf($cpuRtPeriod, '--cpu-rt-period', $cpuRtPeriod);
        $this->docker->addIf($cpuRtRuntime, '--cpu-rt-runtime', $cpuRtRuntime);
        $this->docker->addIf($cpuShares, '--cpu-shares', $cpuShares);
        $this->docker->addIf($cpus, '--cpus', $cpus);
        $this->docker->addIf($cpusetCpus, '--cpuset-cpus', $cpusetCpus);
        $this->docker->addIf($cpusetMems, '--cpuset-mems', $cpusetMems);
        $this->docker->addIf($detach, '--detach');
        $this->docker->addIf($detachKeys, '--detach-keys', $detachKeys);
        $this->docker->addIf($device, '--device', $device);
        $this->docker->addIf($deviceCgroupRule, '--device-cgroup-rule', $deviceCgroupRule);
        $this->docker->addIf($deviceReadBps, '--device-read-bps', $deviceReadBps);
        $this->docker->addIf($deviceReadIops, '--device-read-iops', $deviceReadIops);
        $this->docker->addIf($deviceWriteBps, '--device-write-bps', $deviceWriteBps);
        $this->docker->addIf($deviceWriteIops, '--device-write-iops', $deviceWriteIops);
        $this->docker->addIf($disableContentTrust, '--disable-content-trust');
        $this->docker->addIf($dns, '--dns', $dns);
        $this->docker->addIf($dnsOption, '--dns-option', $dnsOption);
        $this->docker->addIf($dnsSearch, '--dns-search', $dnsSearch);
        $this->docker->addIf($domainname, '--domainname', $domainname);
        $this->docker->addIf($entrypoint, '--entrypoint', $entrypoint);
        $this->docker->addIf($env, '--env', $env);
        $this->docker->addIf($envFile, '--env-file', $envFile);
        $this->docker->addIf($expose, '--expose', $expose);
        $this->docker->addIf($gpus, '--gpus');
        $this->docker->addIf($groupAdd, '--group-add', $groupAdd);
        $this->docker->addIf($healthCmd, '--health-cmd', $healthCmd);
        $this->docker->addIf($healthInterval, '--health-interval', $healthInterval);
        $this->docker->addIf($healthRetries, '--health-retries', $healthRetries);
        $this->docker->addIf($healthStartInterval, '--health-start-interval', $healthStartInterval);
        $this->docker->addIf($healthStartPeriod, '--health-start-period', $healthStartPeriod);
        $this->docker->addIf($healthTimeout, '--health-timeout', $healthTimeout);
        $this->docker->addIf($help, '--help');
        $this->docker->addIf($hostname, '--hostname', $hostname);
        $this->docker->addIf($init, '--init');
        $this->docker->addIf($interactive, '--interactive');
        $this->docker->addIf($ip, '--ip', $ip);
        $this->docker->addIf($ip6, '--ip6', $ip6);
        $this->docker->addIf($ipc, '--ipc', $ipc);
        $this->docker->addIf($isolation, '--isolation', $isolation);
        $this->docker->addIf($kernelMemory, '--kernel-memory', $kernelMemory);
        $this->docker->addIf($label, '--label', $label);
        $this->docker->addIf($labelFile, '--label-file', $labelFile);
        $this->docker->addIf($link, '--link', $link);
        $this->docker->addIf($linkLocalIp, '--link-local-ip', $linkLocalIp);
        $this->docker->addIf($logDriver, '--log-driver', $logDriver);
        $this->docker->addIf($logOpt, '--log-opt', $logOpt);
        $this->docker->addIf($macAddress, '--mac-address', $macAddress);
        $this->docker->addIf($memory, '--memory', $memory);
        $this->docker->addIf($memoryReservation, '--memory-reservation', $memoryReservation);
        $this->docker->addIf($memorySwap, '--memory-swap', $memorySwap);
        $this->docker->addIf($memorySwappiness, '--memory-swappiness', $memorySwappiness);
        $this->docker->addIf($mount, '--mount');
        $this->docker->addIf($name, '--name', $name);
        $this->docker->addIf($network, '--network');
        $this->docker->addIf($networkAlias, '--network-alias', $networkAlias);
        $this->docker->addIf($noHealthcheck, '--no-healthcheck');
        $this->docker->addIf($oomKillDisable, '--oom-kill-disable');
        $this->docker->addIf($oomScoreAdj, '--oom-score-adj', $oomScoreAdj);
        $this->docker->addIf($pid, '--pid', $pid);
        $this->docker->addIf($pidsLimit, '--pids-limit', $pidsLimit);
        $this->docker->addIf($platform, '--platform', $platform);
        $this->docker->addIf($privileged, '--privileged');
        $this->docker->addIf($publish, '--publish', $publish);
        $this->docker->addIf($pull, '--pull', $pull);
        $this->docker->addIf($quiet, '--quiet');
        $this->docker->addIf($readOnly, '--read-only');
        $this->docker->addIf($restart, '--restart', $restart);
        $this->docker->addIf($rm, '--rm');
        $this->docker->addIf($runtime, '--runtime', $runtime);
        $this->docker->addIf($securityOpt, '--security-opt', $securityOpt);
        $this->docker->addIf($shmSize, '--shm-size', $shmSize);
        $this->docker->addIf($sigProxy, '--sig-proxy');
        $this->docker->addIf($stopSignal, '--stop-signal', $stopSignal);
        $this->docker->addIf($stopTimeout, '--stop-timeout', $stopTimeout);
        $this->docker->addIf($storageOpt, '--storage-opt', $storageOpt);
        $this->docker->addIf($sysctl, '--sysctl', $sysctl);
        $this->docker->addIf($tmpfs, '--tmpfs', $tmpfs);
        $this->docker->addIf($tty, '--tty');
        $this->docker->addIf($ulimit, '--ulimit', $ulimit);
        $this->docker->addIf($user, '--user', $user);
        $this->docker->addIf($userns, '--userns', $userns);
        $this->docker->addIf($uts, '--uts', $uts);
        $this->docker->addIf($volume, '--volume', $volume);
        $this->docker->addIf($volumeDriver, '--volume-driver', $volumeDriver);
        $this->docker->addIf($volumesFrom, '--volumes-from', $volumesFrom);
        $this->docker->addIf($workdir, '--workdir', $workdir);
        $this->docker->addIf($image, null, $image);
        $this->docker->addIf($args, null, $args);

        return $this->docker->runCommand();
    }

    /**
     * Usage:  docker container start [OPTIONS] CONTAINER [CONTAINER...].
     */
    public function start(
        string $container,
        array $containers = [],
        /* Attach STDOUT/STDERR and forward signals */
        bool $attach = false,
        /* Override the key sequence for detaching a container */
        false|string $detachKeys = false,
        /* Attach container's STDIN */
        bool $interactive = false,
    ): Process {
        $this->docker->add('start');
        $this->docker->addIf($attach, '--attach');
        $this->docker->addIf($detachKeys, '--detach-keys', $detachKeys);
        $this->docker->addIf($interactive, '--interactive');
        $this->docker->addIf($container, null, $container);
        $this->docker->addIf($containers, null, $containers);

        return $this->docker->runCommand();
    }

    /**
     * Usage:  docker container stats [OPTIONS] [CONTAINER...].
     */
    public function stats(
        array $containers = [],
        /* Show all containers (default shows just running) */
        bool $all = false,
        /* Format output using a custom template: 'table': Print output in table format with column headers (default) 'table TEMPLATE': Print output in table format using the given Go template 'json': Print in JSON format 'TEMPLATE': Print output using the given Go template. Refer to https://docs.docker.com/go/formatting/ for more information about formatting output with templates */
        false|string $format = false,
        /* Disable streaming stats and only pull the first result */
        bool $noStream = false,
        /* Do not truncate output */
        bool $noTrunc = false,
    ): Process {
        $this->docker->add('stats');
        $this->docker->addIf($all, '--all');
        $this->docker->addIf($format, '--format', $format);
        $this->docker->addIf($noStream, '--no-stream');
        $this->docker->addIf($noTrunc, '--no-trunc');
        $this->docker->addIf($containers, null, $containers);

        return $this->docker->runCommand();
    }

    /**
     * Usage:  docker container stop [OPTIONS] CONTAINER [CONTAINER...].
     */
    public function stop(
        string $container,
        array $containers = [],
        /* Signal to send to the container */
        false|string $signal = false,
        /* Seconds to wait before killing the container */
        false|int $time = false,
    ): Process {
        $this->docker->add('stop');
        $this->docker->addIf($signal, '--signal', $signal);
        $this->docker->addIf($time, '--time', $time);
        $this->docker->addIf($container, null, $container);
        $this->docker->addIf($containers, null, $containers);

        return $this->docker->runCommand();
    }

    /**
     * Usage:  docker container top CONTAINER [ps OPTIONS].
     */
    public function top(string $container): Process
    {
        $this->docker->add('top');

        $this->docker->addIf($container, null, $container);

        return $this->docker->runCommand();
    }

    /**
     * Usage:  docker container unpause CONTAINER [CONTAINER...].
     */
    public function unpause(string $container, array $containers = []): Process
    {
        $this->docker->add('unpause');

        $this->docker->addIf($container, null, $container);
        $this->docker->addIf($containers, null, $containers);

        return $this->docker->runCommand();
    }

    /**
     * Usage:  docker container update [OPTIONS] CONTAINER [CONTAINER...].
     */
    public function update(
        string $container,
        array $containers = [],
        /* Block IO (relative weight), between 10 and 1000, or 0 to disable (default 0) */
        false|int $blkioWeight = false,
        /* Limit CPU CFS (Completely Fair Scheduler) period */
        false|int $cpuPeriod = false,
        /* Limit CPU CFS (Completely Fair Scheduler) quota */
        false|int $cpuQuota = false,
        /* Limit the CPU real-time period in microseconds */
        false|int $cpuRtPeriod = false,
        /* Limit the CPU real-time runtime in microseconds */
        false|int $cpuRtRuntime = false,
        /* CPU shares (relative weight) */
        false|int $cpuShares = false,
        /* Number of CPUs */
        false|int $cpus = false,
        /* CPUs in which to allow execution (0-3, 0,1) */
        false|string $cpusetCpus = false,
        /* MEMs in which to allow execution (0-3, 0,1) */
        false|string $cpusetMems = false,
        /* Memory limit */
        false|int $memory = false,
        /* Memory soft limit */
        false|int $memoryReservation = false,
        /* Swap limit equal to memory plus swap: -1 to enable unlimited swap */
        false|int $memorySwap = false,
        /* Tune container pids limit (set -1 for unlimited) */
        false|int $pidsLimit = false,
        /* Restart policy to apply when a container exits */
        false|string $restart = false,
    ): Process {
        $this->docker->add('update');
        $this->docker->addIf($blkioWeight, '--blkio-weight', $blkioWeight);
        $this->docker->addIf($cpuPeriod, '--cpu-period', $cpuPeriod);
        $this->docker->addIf($cpuQuota, '--cpu-quota', $cpuQuota);
        $this->docker->addIf($cpuRtPeriod, '--cpu-rt-period', $cpuRtPeriod);
        $this->docker->addIf($cpuRtRuntime, '--cpu-rt-runtime', $cpuRtRuntime);
        $this->docker->addIf($cpuShares, '--cpu-shares', $cpuShares);
        $this->docker->addIf($cpus, '--cpus', $cpus);
        $this->docker->addIf($cpusetCpus, '--cpuset-cpus', $cpusetCpus);
        $this->docker->addIf($cpusetMems, '--cpuset-mems', $cpusetMems);
        $this->docker->addIf($memory, '--memory', $memory);
        $this->docker->addIf($memoryReservation, '--memory-reservation', $memoryReservation);
        $this->docker->addIf($memorySwap, '--memory-swap', $memorySwap);
        $this->docker->addIf($pidsLimit, '--pids-limit', $pidsLimit);
        $this->docker->addIf($restart, '--restart', $restart);
        $this->docker->addIf($container, null, $container);
        $this->docker->addIf($containers, null, $containers);

        return $this->docker->runCommand();
    }

    /**
     * Usage:  docker container wait CONTAINER [CONTAINER...].
     */
    public function wait(string $container, array $containers = []): Process
    {
        $this->docker->add('wait');

        $this->docker->addIf($container, null, $container);
        $this->docker->addIf($containers, null, $containers);

        return $this->docker->runCommand();
    }
}

class DockerContext
{
    public function __construct(
        private readonly Docker $docker,
    ) {
        $this->docker->add('context');
    }

    /**
     * Usage:  docker context create [OPTIONS] CONTEXT.
     */
    public function create(
        string $context,
        /* Description of the context */
        false|string $description = false,
        /* stringToString set the docker endpoint (default []) */
        bool $docker = false,
        /* create context from a named context */
        false|string $from = false,
    ): Process {
        $this->docker->add('create');
        $this->docker->addIf($description, '--description', $description);
        $this->docker->addIf($docker, '--docker');
        $this->docker->addIf($from, '--from', $from);
        $this->docker->addIf($context, null, $context);

        return $this->docker->runCommand();
    }

    /**
     * Usage:  docker context export [OPTIONS] CONTEXT [FILE|-].
     */
    public function export(string $context, string $file): Process
    {
        $this->docker->add('export');

        $this->docker->addIf($context, null, $context);
        $this->docker->addIf($file, null, $file);

        return $this->docker->runCommand();
    }

    /**
     * Usage:  docker context import CONTEXT FILE|-.
     */
    public function import(string $context, string $file): Process
    {
        $this->docker->add('import');

        $this->docker->addIf($context, null, $context);
        $this->docker->addIf($file, null, $file);

        return $this->docker->runCommand();
    }

    /**
     * Usage:  docker context inspect [OPTIONS] [CONTEXT] [CONTEXT...].
     */
    public function inspect(
        string $context,
        array $contexts = [],
        /* Format output using a custom template: 'json': Print in JSON format 'TEMPLATE': Print output using the given Go template. Refer to https://docs.docker.com/go/formatting/ for more information about formatting output with templates */
        false|string $format = false,
    ): Process {
        $this->docker->add('inspect');
        $this->docker->addIf($format, '--format', $format);
        $this->docker->addIf($context, null, $context);
        $this->docker->addIf($contexts, null, $contexts);

        return $this->docker->runCommand();
    }

    /**
     * Usage:  docker context ls [OPTIONS].
     */
    public function ls(
        /* Format output using a custom template: 'table': Print output in table format with column headers (default) 'table TEMPLATE': Print output in table format using the given Go template 'json': Print in JSON format 'TEMPLATE': Print output using the given Go template. Refer to https://docs.docker.com/go/formatting/ for more information about formatting output with templates */
        false|string $format = false,
        /* Only show context names */
        bool $quiet = false,
    ): Process {
        $this->docker->add('ls');
        $this->docker->addIf($format, '--format', $format);
        $this->docker->addIf($quiet, '--quiet');

        return $this->docker->runCommand();
    }

    /**
     * Usage:  docker context rm CONTEXT [CONTEXT...].
     */
    public function rm(
        string $context,
        array $contexts = [],
        /* Force the removal of a context in use */
        bool $force = false,
    ): Process {
        $this->docker->add('rm');
        $this->docker->addIf($force, '--force');
        $this->docker->addIf($context, null, $context);
        $this->docker->addIf($contexts, null, $contexts);

        return $this->docker->runCommand();
    }

    /**
     * Usage:  docker context show.
     */
    public function show(): Process
    {
        $this->docker->add('show');

        return $this->docker->runCommand();
    }

    /**
     * Usage:  docker context update [OPTIONS] CONTEXT.
     */
    public function update(
        string $context,
        /* Description of the context */
        false|string $description = false,
        /* stringToString set the docker endpoint (default []) */
        bool $docker = false,
    ): Process {
        $this->docker->add('update');
        $this->docker->addIf($description, '--description', $description);
        $this->docker->addIf($docker, '--docker');
        $this->docker->addIf($context, null, $context);

        return $this->docker->runCommand();
    }

    /**
     * Usage:  docker context use CONTEXT.
     */
    public function use(string $context): Process
    {
        $this->docker->add('use');

        $this->docker->addIf($context, null, $context);

        return $this->docker->runCommand();
    }
}

class DockerImage
{
    public function __construct(
        private readonly Docker $docker,
    ) {
        $this->docker->add('image');
    }

    /**
     * Usage:  docker buildx build [OPTIONS] PATH | URL | -.
     */
    public function build(
        string $path,
        string $url,
        /* strings Add a custom host-to-IP mapping (format: "host:ip") */
        bool $addHost = false,
        /* strings Allow extra privileged entitlement (e.g., "network.host", "security.insecure") */
        bool $allow = false,
        /* Add annotation to the image */
        false|array $annotation = false,
        /* Attestation parameters (format: "type=sbom,generator=image") */
        false|array $attest = false,
        /* Set build-time variables */
        false|array $buildArg = false,
        /* Additional build contexts (e.g., name=path) */
        false|array $buildContext = false,
        /* Override the configured builder instance (default "default") */
        false|string $builder = false,
        /* External cache sources (e.g., "user/app:cache", "type=local,src=path/to/dir") */
        false|array $cacheFrom = false,
        /* Cache export destinations (e.g., "user/app:cache", "type=local,dest=path/to/dir") */
        false|array $cacheTo = false,
        /* Set the parent cgroup for the "RUN" instructions during build */
        false|string $cgroupParent = false,
        /* Name of the Dockerfile (default: "PATH/Dockerfile") */
        false|string $file = false,
        /* Write the image ID to the file */
        false|string $iidfile = false,
        /* Set metadata for an image */
        false|array $label = false,
        /* Shorthand for "--output=type=docker" */
        bool $load = false,
        /* Write build result metadata to the file */
        false|string $metadataFile = false,
        /* Set the networking mode for the "RUN" instructions during build (default "default") */
        false|string $network = false,
        /* Do not use cache when building the image */
        bool $noCache = false,
        /* Do not cache specified stages */
        false|array $noCacheFilter = false,
        /* Output destination (format: "type=local,dest=path") */
        false|array $output = false,
        /* Set target platform for build */
        false|array $platform = false,
        /* Set type of progress output ("auto", "plain", "tty"). Use plain to show container output (default "auto") */
        false|string $progress = false,
        /* Shorthand for "--attest=type=provenance" */
        false|string $provenance = false,
        /* Always attempt to pull all referenced images */
        bool $pull = false,
        /* Shorthand for "--output=type=registry" */
        bool $push = false,
        /* Suppress the build output and print image ID on success */
        bool $quiet = false,
        /* Shorthand for "--attest=type=sbom" */
        false|string $sbom = false,
        /* Secret to expose to the build (format: "id=mysecret[,src=/local/secret]") */
        false|array $secret = false,
        /* Size of "/dev/shm" */
        false|int $shmSize = false,
        /* SSH agent socket or keys to expose to the build (format: "default|<id>[=<socket>|<key>[,<key>]]") */
        false|array $ssh = false,
        /* Name and optionally a tag (format: "name:tag") */
        false|array $tag = false,
        /* Set the target build stage to build */
        false|string $target = false,
        /* Ulimit options (default []) */
        false|int $ulimit = false,
    ): Process {
        $this->docker->add('build');
        $this->docker->addIf($addHost, '--add-host');
        $this->docker->addIf($allow, '--allow');
        $this->docker->addIf($annotation, '--annotation', $annotation);
        $this->docker->addIf($attest, '--attest', $attest);
        $this->docker->addIf($buildArg, '--build-arg', $buildArg);
        $this->docker->addIf($buildContext, '--build-context', $buildContext);
        $this->docker->addIf($builder, '--builder', $builder);
        $this->docker->addIf($cacheFrom, '--cache-from', $cacheFrom);
        $this->docker->addIf($cacheTo, '--cache-to', $cacheTo);
        $this->docker->addIf($cgroupParent, '--cgroup-parent', $cgroupParent);
        $this->docker->addIf($file, '--file', $file);
        $this->docker->addIf($iidfile, '--iidfile', $iidfile);
        $this->docker->addIf($label, '--label', $label);
        $this->docker->addIf($load, '--load');
        $this->docker->addIf($metadataFile, '--metadata-file', $metadataFile);
        $this->docker->addIf($network, '--network', $network);
        $this->docker->addIf($noCache, '--no-cache');
        $this->docker->addIf($noCacheFilter, '--no-cache-filter', $noCacheFilter);
        $this->docker->addIf($output, '--output', $output);
        $this->docker->addIf($platform, '--platform', $platform);
        $this->docker->addIf($progress, '--progress', $progress);
        $this->docker->addIf($provenance, '--provenance', $provenance);
        $this->docker->addIf($pull, '--pull');
        $this->docker->addIf($push, '--push');
        $this->docker->addIf($quiet, '--quiet');
        $this->docker->addIf($sbom, '--sbom', $sbom);
        $this->docker->addIf($secret, '--secret', $secret);
        $this->docker->addIf($shmSize, '--shm-size', $shmSize);
        $this->docker->addIf($ssh, '--ssh', $ssh);
        $this->docker->addIf($tag, '--tag', $tag);
        $this->docker->addIf($target, '--target', $target);
        $this->docker->addIf($ulimit, '--ulimit', $ulimit);
        $this->docker->addIf($path, null, $path);
        $this->docker->addIf($url, null, $url);

        return $this->docker->runCommand();
    }

    /**
     * Usage:  docker image history [OPTIONS] IMAGE.
     */
    public function history(
        string $image,
        /* Format output using a custom template: 'table': Print output in table format with column headers (default) 'table TEMPLATE': Print output in table format using the given Go template 'json': Print in JSON format 'TEMPLATE': Print output using the given Go template. Refer to https://docs.docker.com/go/formatting/ for more information about formatting output with templates -H, --human Print sizes and dates in human readable format (default true) */
        false|string $format = false,
        /* Don't truncate output */
        bool $noTrunc = false,
        /* Only show image IDs */
        bool $quiet = false,
    ): Process {
        $this->docker->add('history');
        $this->docker->addIf($format, '--format', $format);
        $this->docker->addIf($noTrunc, '--no-trunc');
        $this->docker->addIf($quiet, '--quiet');
        $this->docker->addIf($image, null, $image);

        return $this->docker->runCommand();
    }

    /**
     * Usage:  docker image import [OPTIONS] file|URL|- [REPOSITORY[:TAG]].
     */
    public function import(
        string $url,
        string $repository,
        string $tag,
        /* Apply Dockerfile instruction to the created image */
        false|array $change = false,
        /* Set commit message for imported image */
        false|string $message = false,
        /* Set platform if server is multi-platform capable */
        false|string $platform = false,
    ): Process {
        $this->docker->add('import');
        $this->docker->addIf($change, '--change', $change);
        $this->docker->addIf($message, '--message', $message);
        $this->docker->addIf($platform, '--platform', $platform);
        $this->docker->addIf($url, null, $url);
        $this->docker->addIf($repository, null, $repository);
        $this->docker->addIf($tag, null, $tag);

        return $this->docker->runCommand();
    }

    /**
     * Usage:  docker image inspect [OPTIONS] IMAGE [IMAGE...].
     */
    public function inspect(
        string $image,
        array $images = [],
        /* Format output using a custom template: 'json': Print in JSON format 'TEMPLATE': Print output using the given Go template. Refer to https://docs.docker.com/go/formatting/ for more information about formatting output with templates */
        false|string $format = false,
    ): Process {
        $this->docker->add('inspect');
        $this->docker->addIf($format, '--format', $format);
        $this->docker->addIf($image, null, $image);
        $this->docker->addIf($images, null, $images);

        return $this->docker->runCommand();
    }

    /**
     * Usage:  docker image load [OPTIONS].
     */
    public function load(
        /* Read from tar archive file, instead of STDIN */
        false|string $input = false,
        /* Suppress the load output */
        bool $quiet = false,
    ): Process {
        $this->docker->add('load');
        $this->docker->addIf($input, '--input', $input);
        $this->docker->addIf($quiet, '--quiet');

        return $this->docker->runCommand();
    }

    /**
     * Usage:  docker image ls [OPTIONS] [REPOSITORY[:TAG]].
     */
    public function ls(
        string $repository,
        string $tag,
        /* Show all images (default hides intermediate images) */
        bool $all = false,
        /* Show digests */
        bool $digests = false,
        /* Filter output based on conditions provided */
        false|array $filter = false,
        /* Format output using a custom template: 'table': Print output in table format with column headers (default) 'table TEMPLATE': Print output in table format using the given Go template 'json': Print in JSON format 'TEMPLATE': Print output using the given Go template. Refer to https://docs.docker.com/go/formatting/ for more information about formatting output with templates */
        false|string $format = false,
        /* Don't truncate output */
        bool $noTrunc = false,
        /* Only show image IDs */
        bool $quiet = false,
    ): Process {
        $this->docker->add('ls');
        $this->docker->addIf($all, '--all');
        $this->docker->addIf($digests, '--digests');
        $this->docker->addIf($filter, '--filter', $filter);
        $this->docker->addIf($format, '--format', $format);
        $this->docker->addIf($noTrunc, '--no-trunc');
        $this->docker->addIf($quiet, '--quiet');
        $this->docker->addIf($repository, null, $repository);
        $this->docker->addIf($tag, null, $tag);

        return $this->docker->runCommand();
    }

    /**
     * Usage:  docker image prune [OPTIONS].
     */
    public function prune(
        /* Remove all unused images, not just dangling ones */
        bool $all = false,
        /* Provide filter values (e.g. "until=<timestamp>") */
        false|array $filter = false,
        /* Do not prompt for confirmation */
        bool $force = false,
    ): Process {
        $this->docker->add('prune');
        $this->docker->addIf($all, '--all');
        $this->docker->addIf($filter, '--filter', $filter);
        $this->docker->addIf($force, '--force');

        return $this->docker->runCommand();
    }

    /**
     * Usage:  docker image pull [OPTIONS] NAME[:TAG|@DIGEST].
     */
    public function pull(
        string $name,
        string $tag,
        string $digest,
        /* Download all tagged images in the repository */
        bool $allTags = false,
        /* Skip image verification (default true) */
        bool $disableContentTrust = false,
        /* Set platform if server is multi-platform capable */
        false|string $platform = false,
        /* Suppress verbose output */
        bool $quiet = false,
    ): Process {
        $this->docker->add('pull');
        $this->docker->addIf($allTags, '--all-tags');
        $this->docker->addIf($disableContentTrust, '--disable-content-trust');
        $this->docker->addIf($platform, '--platform', $platform);
        $this->docker->addIf($quiet, '--quiet');
        $this->docker->addIf($name, null, $name);
        $this->docker->addIf($tag, null, $tag);
        $this->docker->addIf($digest, null, $digest);

        return $this->docker->runCommand();
    }

    /**
     * Usage:  docker image push [OPTIONS] NAME[:TAG].
     */
    public function push(
        string $name,
        string $tag,
        /* Push all tags of an image to the repository */
        bool $allTags = false,
        /* Skip image signing (default true) */
        bool $disableContentTrust = false,
        /* Suppress verbose output */
        bool $quiet = false,
    ): Process {
        $this->docker->add('push');
        $this->docker->addIf($allTags, '--all-tags');
        $this->docker->addIf($disableContentTrust, '--disable-content-trust');
        $this->docker->addIf($quiet, '--quiet');
        $this->docker->addIf($name, null, $name);
        $this->docker->addIf($tag, null, $tag);

        return $this->docker->runCommand();
    }

    /**
     * Usage:  docker image rm [OPTIONS] IMAGE [IMAGE...].
     */
    public function rm(
        string $image,
        array $images = [],
        /* Force removal of the image */
        bool $force = false,
        /* Do not delete untagged parents */
        bool $noPrune = false,
    ): Process {
        $this->docker->add('rm');
        $this->docker->addIf($force, '--force');
        $this->docker->addIf($noPrune, '--no-prune');
        $this->docker->addIf($image, null, $image);
        $this->docker->addIf($images, null, $images);

        return $this->docker->runCommand();
    }

    /**
     * Usage:  docker image save [OPTIONS] IMAGE [IMAGE...].
     */
    public function save(
        string $image,
        array $images = [],
        /* Write to a file, instead of STDOUT */
        false|string $output = false,
    ): Process {
        $this->docker->add('save');
        $this->docker->addIf($output, '--output', $output);
        $this->docker->addIf($image, null, $image);
        $this->docker->addIf($images, null, $images);

        return $this->docker->runCommand();
    }

    /**
     * Usage:  docker image tag SOURCE_IMAGE[:TAG] TARGET_IMAGE[:TAG].
     */
    public function tag(string $source, string $image, string $tag, string $target): Process
    {
        $this->docker->add('tag');

        $this->docker->addIf($source, null, $source);
        $this->docker->addIf($image, null, $image);
        $this->docker->addIf($tag, null, $tag);
        $this->docker->addIf($target, null, $target);
        $this->docker->addIf($image, null, $image);
        $this->docker->addIf($tag, null, $tag);

        return $this->docker->runCommand();
    }
}

class DockerManifest
{
    public function __construct(
        private readonly Docker $docker,
    ) {
        $this->docker->add('manifest');
    }

    /**
     * Usage:  docker manifest annotate [OPTIONS] MANIFEST_LIST MANIFEST.
     */
    public function annotate(
        string $manifest,
        string $list,
        /* Set architecture */
        false|string $arch = false,
        /* Set operating system */
        false|string $os = false,
        /* strings Set operating system feature */
        bool $osFeatures = false,
        /* Set operating system version */
        false|string $osVersion = false,
        /* Set architecture variant */
        false|string $variant = false,
    ): Process {
        $this->docker->add('annotate');
        $this->docker->addIf($arch, '--arch', $arch);
        $this->docker->addIf($os, '--os', $os);
        $this->docker->addIf($osFeatures, '--os-features');
        $this->docker->addIf($osVersion, '--os-version', $osVersion);
        $this->docker->addIf($variant, '--variant', $variant);
        $this->docker->addIf($manifest, null, $manifest);
        $this->docker->addIf($list, null, $list);
        $this->docker->addIf($manifest, null, $manifest);

        return $this->docker->runCommand();
    }

    /**
     * Usage:  docker manifest create MANIFEST_LIST MANIFEST [MANIFEST...].
     */
    public function create(
        string $manifest,
        string $list,
        array $manifests = [],
        /* Amend an existing manifest list */
        bool $amend = false,
        /* Allow communication with an insecure registry */
        bool $insecure = false,
    ): Process {
        $this->docker->add('create');
        $this->docker->addIf($amend, '--amend');
        $this->docker->addIf($insecure, '--insecure');
        $this->docker->addIf($manifest, null, $manifest);
        $this->docker->addIf($list, null, $list);
        $this->docker->addIf($manifest, null, $manifest);
        $this->docker->addIf($manifests, null, $manifests);

        return $this->docker->runCommand();
    }

    /**
     * Usage:  docker manifest inspect [OPTIONS] [MANIFEST_LIST] MANIFEST.
     */
    public function inspect(
        string $manifest,
        string $list,
        /* Allow communication with an insecure registry */
        bool $insecure = false,
        /* Output additional info including layers and platform */
        bool $verbose = false,
    ): Process {
        $this->docker->add('inspect');
        $this->docker->addIf($insecure, '--insecure');
        $this->docker->addIf($verbose, '--verbose');
        $this->docker->addIf($manifest, null, $manifest);
        $this->docker->addIf($list, null, $list);
        $this->docker->addIf($manifest, null, $manifest);

        return $this->docker->runCommand();
    }

    /**
     * Usage:  docker manifest push [OPTIONS] MANIFEST_LIST.
     */
    public function push(
        string $manifest,
        string $list,
        /* Allow push to an insecure registry */
        bool $insecure = false,
        /* Remove the local manifest list after push */
        bool $purge = false,
    ): Process {
        $this->docker->add('push');
        $this->docker->addIf($insecure, '--insecure');
        $this->docker->addIf($purge, '--purge');
        $this->docker->addIf($manifest, null, $manifest);
        $this->docker->addIf($list, null, $list);

        return $this->docker->runCommand();
    }

    /**
     * Usage:  docker manifest rm MANIFEST_LIST [MANIFEST_LIST...].
     */
    public function rm(string $manifest, string $list, array $lists = []): Process
    {
        $this->docker->add('rm');

        $this->docker->addIf($manifest, null, $manifest);
        $this->docker->addIf($list, null, $list);
        $this->docker->addIf($manifest, null, $manifest);
        $this->docker->addIf($lists, null, $lists);

        return $this->docker->runCommand();
    }
}

class DockerNetwork
{
    public function __construct(
        private readonly Docker $docker,
    ) {
        $this->docker->add('network');
    }

    /**
     * Usage:  docker network connect [OPTIONS] NETWORK CONTAINER.
     */
    public function connect(
        string $network,
        string $container,
        /* strings Add network-scoped alias for the container */
        bool $alias = false,
        /* strings driver options for the network */
        bool $driverOpt = false,
        /* IPv4 address (e.g., "172.30.100.104") */
        false|string $ip = false,
        /* IPv6 address (e.g., "2001:db8::33") */
        false|string $ip6 = false,
        /* Add link to another container */
        false|array $link = false,
        /* strings Add a link-local address for the container */
        bool $linkLocalIp = false,
    ): Process {
        $this->docker->add('connect');
        $this->docker->addIf($alias, '--alias');
        $this->docker->addIf($driverOpt, '--driver-opt');
        $this->docker->addIf($ip, '--ip', $ip);
        $this->docker->addIf($ip6, '--ip6', $ip6);
        $this->docker->addIf($link, '--link', $link);
        $this->docker->addIf($linkLocalIp, '--link-local-ip');
        $this->docker->addIf($network, null, $network);
        $this->docker->addIf($container, null, $container);

        return $this->docker->runCommand();
    }

    /**
     * Usage:  docker network create [OPTIONS] NETWORK.
     */
    public function create(
        string $network,
        /* Enable manual container attachment */
        bool $attachable = false,
        /* Auxiliary IPv4 or IPv6 addresses used by Network driver (default map[]) */
        false|array $auxAddress = false,
        /* The network from which to copy the configuration */
        false|string $configFrom = false,
        /* Create a configuration only network */
        bool $configOnly = false,
        /* Driver to manage the Network (default "bridge") */
        false|string $driver = false,
        /* strings IPv4 or IPv6 Gateway for the master subnet */
        bool $gateway = false,
        /* Create swarm routing-mesh network */
        bool $ingress = false,
        /* Restrict external access to the network */
        bool $internal = false,
        /* strings Allocate container ip from a sub-range */
        bool $ipRange = false,
        /* IP Address Management Driver (default "default") */
        false|string $ipamDriver = false,
        /* Set IPAM driver specific options (default map[]) */
        false|array $ipamOpt = false,
        /* Enable IPv6 networking */
        bool $ipv6 = false,
        /* Set metadata on a network */
        false|array $label = false,
        /* Set driver specific options (default map[]) */
        false|array $opt = false,
        /* Control the network's scope */
        false|string $scope = false,
        /* strings Subnet in CIDR format that represents a network segment */
        bool $subnet = false,
    ): Process {
        $this->docker->add('create');
        $this->docker->addIf($attachable, '--attachable');
        $this->docker->addIf($auxAddress, '--aux-address', $auxAddress);
        $this->docker->addIf($configFrom, '--config-from', $configFrom);
        $this->docker->addIf($configOnly, '--config-only');
        $this->docker->addIf($driver, '--driver', $driver);
        $this->docker->addIf($gateway, '--gateway');
        $this->docker->addIf($ingress, '--ingress');
        $this->docker->addIf($internal, '--internal');
        $this->docker->addIf($ipRange, '--ip-range');
        $this->docker->addIf($ipamDriver, '--ipam-driver', $ipamDriver);
        $this->docker->addIf($ipamOpt, '--ipam-opt', $ipamOpt);
        $this->docker->addIf($ipv6, '--ipv6');
        $this->docker->addIf($label, '--label', $label);
        $this->docker->addIf($opt, '--opt', $opt);
        $this->docker->addIf($scope, '--scope', $scope);
        $this->docker->addIf($subnet, '--subnet');
        $this->docker->addIf($network, null, $network);

        return $this->docker->runCommand();
    }

    /**
     * Usage:  docker network disconnect [OPTIONS] NETWORK CONTAINER.
     */
    public function disconnect(
        string $network,
        string $container,
        /* Force the container to disconnect from a network */
        bool $force = false,
    ): Process {
        $this->docker->add('disconnect');
        $this->docker->addIf($force, '--force');
        $this->docker->addIf($network, null, $network);
        $this->docker->addIf($container, null, $container);

        return $this->docker->runCommand();
    }

    /**
     * Usage:  docker network inspect [OPTIONS] NETWORK [NETWORK...].
     */
    public function inspect(
        string $network,
        array $networks = [],
        /* Format output using a custom template: 'json': Print in JSON format 'TEMPLATE': Print output using the given Go template. Refer to https://docs.docker.com/go/formatting/ for more information about formatting output with templates */
        false|string $format = false,
        /* Verbose output for diagnostics */
        bool $verbose = false,
    ): Process {
        $this->docker->add('inspect');
        $this->docker->addIf($format, '--format', $format);
        $this->docker->addIf($verbose, '--verbose');
        $this->docker->addIf($network, null, $network);
        $this->docker->addIf($networks, null, $networks);

        return $this->docker->runCommand();
    }

    /**
     * Usage:  docker network ls [OPTIONS].
     */
    public function ls(
        /* Provide filter values (e.g. "driver=bridge") */
        false|array $filter = false,
        /* Format output using a custom template: 'table': Print output in table format with column headers (default) 'table TEMPLATE': Print output in table format using the given Go template 'json': Print in JSON format 'TEMPLATE': Print output using the given Go template. Refer to https://docs.docker.com/go/formatting/ for more information about formatting output with templates */
        false|string $format = false,
        /* Do not truncate the output */
        bool $noTrunc = false,
        /* Only display network IDs */
        bool $quiet = false,
    ): Process {
        $this->docker->add('ls');
        $this->docker->addIf($filter, '--filter', $filter);
        $this->docker->addIf($format, '--format', $format);
        $this->docker->addIf($noTrunc, '--no-trunc');
        $this->docker->addIf($quiet, '--quiet');

        return $this->docker->runCommand();
    }

    /**
     * Usage:  docker network prune [OPTIONS].
     */
    public function prune(
        /* Provide filter values (e.g. "until=<timestamp>") */
        false|array $filter = false,
        /* Do not prompt for confirmation */
        bool $force = false,
    ): Process {
        $this->docker->add('prune');
        $this->docker->addIf($filter, '--filter', $filter);
        $this->docker->addIf($force, '--force');

        return $this->docker->runCommand();
    }

    /**
     * Usage:  docker network rm NETWORK [NETWORK...].
     */
    public function rm(
        string $network,
        array $networks = [],
        /* Do not error if the network does not exist */
        bool $force = false,
    ): Process {
        $this->docker->add('rm');
        $this->docker->addIf($force, '--force');
        $this->docker->addIf($network, null, $network);
        $this->docker->addIf($networks, null, $networks);

        return $this->docker->runCommand();
    }
}

class DockerPlugin
{
    public function __construct(
        private readonly Docker $docker,
    ) {
        $this->docker->add('plugin');
    }

    /**
     * Usage:  docker plugin create [OPTIONS] PLUGIN PLUGIN-DATA-DIR.
     */
    public function create(
        string $plugin,
        string $data,
        string $dir,
        /* Compress the context using gzip */
        bool $compress = false,
    ): Process {
        $this->docker->add('create');
        $this->docker->addIf($compress, '--compress');
        $this->docker->addIf($plugin, null, $plugin);
        $this->docker->addIf($plugin, null, $plugin);
        $this->docker->addIf($data, null, $data);
        $this->docker->addIf($dir, null, $dir);

        return $this->docker->runCommand();
    }

    /**
     * Usage:  docker plugin disable [OPTIONS] PLUGIN.
     */
    public function disable(
        string $plugin,
        /* Force the disable of an active plugin */
        bool $force = false,
    ): Process {
        $this->docker->add('disable');
        $this->docker->addIf($force, '--force');
        $this->docker->addIf($plugin, null, $plugin);

        return $this->docker->runCommand();
    }

    /**
     * Usage:  docker plugin enable [OPTIONS] PLUGIN.
     */
    public function enable(
        string $plugin,
        /* HTTP client timeout (in seconds) (default 30) */
        false|int $timeout = false,
    ): Process {
        $this->docker->add('enable');
        $this->docker->addIf($timeout, '--timeout', $timeout);
        $this->docker->addIf($plugin, null, $plugin);

        return $this->docker->runCommand();
    }

    /**
     * Usage:  docker plugin inspect [OPTIONS] PLUGIN [PLUGIN...].
     */
    public function inspect(
        string $plugin,
        array $plugins = [],
        /* Format output using a custom template: 'json': Print in JSON format 'TEMPLATE': Print output using the given Go template. Refer to https://docs.docker.com/go/formatting/ for more information about formatting output with templates */
        false|string $format = false,
    ): Process {
        $this->docker->add('inspect');
        $this->docker->addIf($format, '--format', $format);
        $this->docker->addIf($plugin, null, $plugin);
        $this->docker->addIf($plugins, null, $plugins);

        return $this->docker->runCommand();
    }

    /**
     * Usage:  docker plugin install [OPTIONS] PLUGIN [KEY=VALUE...].
     */
    public function install(
        string $plugin,
        string $key,
        array $values = [],
        /* Local name for plugin */
        false|string $alias = false,
        /* Do not enable the plugin on install */
        bool $disable = false,
        /* Skip image verification (default true) */
        bool $disableContentTrust = false,
        /* Grant all permissions necessary to run the plugin */
        bool $grantAllPermissions = false,
    ): Process {
        $this->docker->add('install');
        $this->docker->addIf($alias, '--alias', $alias);
        $this->docker->addIf($disable, '--disable');
        $this->docker->addIf($disableContentTrust, '--disable-content-trust');
        $this->docker->addIf($grantAllPermissions, '--grant-all-permissions');
        $this->docker->addIf($plugin, null, $plugin);
        $this->docker->addIf($key, null, $key);
        $this->docker->addIf($values, null, $values);

        return $this->docker->runCommand();
    }

    /**
     * Usage:  docker plugin ls [OPTIONS].
     */
    public function ls(
        /* Provide filter values (e.g. "enabled=true") */
        false|array $filter = false,
        /* Format output using a custom template: 'table': Print output in table format with column headers (default) 'table TEMPLATE': Print output in table format using the given Go template 'json': Print in JSON format 'TEMPLATE': Print output using the given Go template. Refer to https://docs.docker.com/go/formatting/ for more information about formatting output with templates */
        false|string $format = false,
        /* Don't truncate output */
        bool $noTrunc = false,
        /* Only display plugin IDs */
        bool $quiet = false,
    ): Process {
        $this->docker->add('ls');
        $this->docker->addIf($filter, '--filter', $filter);
        $this->docker->addIf($format, '--format', $format);
        $this->docker->addIf($noTrunc, '--no-trunc');
        $this->docker->addIf($quiet, '--quiet');

        return $this->docker->runCommand();
    }

    /**
     * Usage:  docker plugin push [OPTIONS] PLUGIN[:TAG].
     */
    public function push(
        string $plugin,
        string $tag,
        /* Skip image signing (default true) */
        bool $disableContentTrust = false,
    ): Process {
        $this->docker->add('push');
        $this->docker->addIf($disableContentTrust, '--disable-content-trust');
        $this->docker->addIf($plugin, null, $plugin);
        $this->docker->addIf($tag, null, $tag);

        return $this->docker->runCommand();
    }

    /**
     * Usage:  docker plugin rm [OPTIONS] PLUGIN [PLUGIN...].
     */
    public function rm(
        string $plugin,
        array $plugins = [],
        /* Force the removal of an active plugin */
        bool $force = false,
    ): Process {
        $this->docker->add('rm');
        $this->docker->addIf($force, '--force');
        $this->docker->addIf($plugin, null, $plugin);
        $this->docker->addIf($plugins, null, $plugins);

        return $this->docker->runCommand();
    }

    /**
     * Usage:  docker plugin set PLUGIN KEY=VALUE [KEY=VALUE...].
     */
    public function set(string $plugin, string $key, string $value, array $values = []): Process
    {
        $this->docker->add('set');

        $this->docker->addIf($plugin, null, $plugin);
        $this->docker->addIf($key, null, $key);
        $this->docker->addIf($value, null, $value);
        $this->docker->addIf($key, null, $key);
        $this->docker->addIf($values, null, $values);

        return $this->docker->runCommand();
    }

    /**
     * Usage:  docker plugin upgrade [OPTIONS] PLUGIN [REMOTE].
     */
    public function upgrade(
        string $plugin,
        string $remote,
        /* Skip image verification (default true) */
        bool $disableContentTrust = false,
        /* Grant all permissions necessary to run the plugin */
        bool $grantAllPermissions = false,
        /* Do not check if specified remote plugin matches existing plugin image */
        bool $skipRemoteCheck = false,
    ): Process {
        $this->docker->add('upgrade');
        $this->docker->addIf($disableContentTrust, '--disable-content-trust');
        $this->docker->addIf($grantAllPermissions, '--grant-all-permissions');
        $this->docker->addIf($skipRemoteCheck, '--skip-remote-check');
        $this->docker->addIf($plugin, null, $plugin);
        $this->docker->addIf($remote, null, $remote);

        return $this->docker->runCommand();
    }
}

class DockerSystem
{
    public function __construct(
        private readonly Docker $docker,
    ) {
        $this->docker->add('system');
    }

    /**
     * Usage:  docker system df [OPTIONS].
     */
    public function df(
        /* Format output using a custom template: 'table': Print output in table format with column headers (default) 'table TEMPLATE': Print output in table format using the given Go template 'json': Print in JSON format 'TEMPLATE': Print output using the given Go template. Refer to https://docs.docker.com/go/formatting/ for more information about formatting output with templates */
        false|string $format = false,
        /* Show detailed information on space usage */
        bool $verbose = false,
    ): Process {
        $this->docker->add('df');
        $this->docker->addIf($format, '--format', $format);
        $this->docker->addIf($verbose, '--verbose');

        return $this->docker->runCommand();
    }

    /**
     * Usage:  docker system events [OPTIONS].
     */
    public function events(
        /* Filter output based on conditions provided */
        false|array $filter = false,
        /* Format output using a custom template: 'json': Print in JSON format 'TEMPLATE': Print output using the given Go template. Refer to https://docs.docker.com/go/formatting/ for more information about formatting output with templates */
        false|string $format = false,
        /* Show all events created since timestamp */
        false|string $since = false,
        /* Stream events until this timestamp */
        false|string $until = false,
    ): Process {
        $this->docker->add('events');
        $this->docker->addIf($filter, '--filter', $filter);
        $this->docker->addIf($format, '--format', $format);
        $this->docker->addIf($since, '--since', $since);
        $this->docker->addIf($until, '--until', $until);

        return $this->docker->runCommand();
    }

    /**
     * Usage:  docker system info [OPTIONS].
     */
    public function info(
        /* Format output using a custom template: 'json': Print in JSON format 'TEMPLATE': Print output using the given Go template. Refer to https://docs.docker.com/go/formatting/ for more information about formatting output with templates */
        false|string $format = false,
    ): Process {
        $this->docker->add('info');
        $this->docker->addIf($format, '--format', $format);

        return $this->docker->runCommand();
    }

    /**
     * Usage:  docker system prune [OPTIONS].
     */
    public function prune(
        /* Remove all unused images not just dangling ones */
        bool $all = false,
        /* Provide filter values (e.g. "label=<key>=<value>") */
        false|array $filter = false,
        /* Do not prompt for confirmation */
        bool $force = false,
        /* Prune anonymous volumes */
        bool $volumes = false,
    ): Process {
        $this->docker->add('prune');
        $this->docker->addIf($all, '--all');
        $this->docker->addIf($filter, '--filter', $filter);
        $this->docker->addIf($force, '--force');
        $this->docker->addIf($volumes, '--volumes');

        return $this->docker->runCommand();
    }
}

class DockerTrust
{
    public function __construct(
        private readonly Docker $docker,
    ) {
        $this->docker->add('trust');
    }

    public function key(): DockerTrustKey
    {
        return new DockerTrustKey(docker: $this->docker);
    }

    public function signer(): DockerTrustSigner
    {
        return new DockerTrustSigner(docker: $this->docker);
    }

    /**
     * Usage:  docker trust inspect IMAGE[:TAG] [IMAGE[:TAG]...].
     */
    public function inspect(
        string $image,
        string $tag,
        array $s = [],
        /* Print the information in a human friendly format */
        bool $pretty = false,
    ): Process {
        $this->docker->add('inspect');
        $this->docker->addIf($pretty, '--pretty');
        $this->docker->addIf($image, null, $image);
        $this->docker->addIf($tag, null, $tag);
        $this->docker->addIf($image, null, $image);
        $this->docker->addIf($tag, null, $tag);
        $this->docker->addIf($s, null, $s);

        return $this->docker->runCommand();
    }

    /**
     * Usage:  docker trust revoke [OPTIONS] IMAGE[:TAG].
     */
    public function revoke(
        string $image,
        string $tag,
        /* Do not prompt for confirmation */
        bool $yes = false,
    ): Process {
        $this->docker->add('revoke');
        $this->docker->addIf($yes, '--yes');
        $this->docker->addIf($image, null, $image);
        $this->docker->addIf($tag, null, $tag);

        return $this->docker->runCommand();
    }

    /**
     * Usage:  docker trust sign IMAGE:TAG.
     */
    public function sign(
        string $image,
        string $tag,
        /* Sign a locally tagged image */
        bool $local = false,
    ): Process {
        $this->docker->add('sign');
        $this->docker->addIf($local, '--local');
        $this->docker->addIf($image, null, $image);
        $this->docker->addIf($tag, null, $tag);

        return $this->docker->runCommand();
    }
}

class DockerVolume
{
    public function __construct(
        private readonly Docker $docker,
    ) {
        $this->docker->add('volume');
    }

    /**
     * Usage:  docker volume create [OPTIONS] [VOLUME].
     */
    public function create(
        string $volume,
        /* Specify volume driver name (default "local") */
        false|string $driver = false,
        /* Set metadata for a volume */
        false|array $label = false,
        /* Set driver specific options (default map[]) */
        false|array $opt = false,
    ): Process {
        $this->docker->add('create');
        $this->docker->addIf($driver, '--driver', $driver);
        $this->docker->addIf($label, '--label', $label);
        $this->docker->addIf($opt, '--opt', $opt);
        $this->docker->addIf($volume, null, $volume);

        return $this->docker->runCommand();
    }

    /**
     * Usage:  docker volume inspect [OPTIONS] VOLUME [VOLUME...].
     */
    public function inspect(
        string $volume,
        array $volumes = [],
        /* Format output using a custom template: 'json': Print in JSON format 'TEMPLATE': Print output using the given Go template. Refer to https://docs.docker.com/go/formatting/ for more information about formatting output with templates */
        false|string $format = false,
    ): Process {
        $this->docker->add('inspect');
        $this->docker->addIf($format, '--format', $format);
        $this->docker->addIf($volume, null, $volume);
        $this->docker->addIf($volumes, null, $volumes);

        return $this->docker->runCommand();
    }

    /**
     * Usage:  docker volume ls [OPTIONS].
     */
    public function ls(
        /* Provide filter values (e.g. "dangling=true") */
        false|array $filter = false,
        /* Format output using a custom template: 'table': Print output in table format with column headers (default) 'table TEMPLATE': Print output in table format using the given Go template 'json': Print in JSON format 'TEMPLATE': Print output using the given Go template. Refer to https://docs.docker.com/go/formatting/ for more information about formatting output with templates */
        false|string $format = false,
        /* Only display volume names */
        bool $quiet = false,
    ): Process {
        $this->docker->add('ls');
        $this->docker->addIf($filter, '--filter', $filter);
        $this->docker->addIf($format, '--format', $format);
        $this->docker->addIf($quiet, '--quiet');

        return $this->docker->runCommand();
    }

    /**
     * Usage:  docker volume prune [OPTIONS].
     */
    public function prune(
        /* Remove all unused volumes, not just anonymous ones */
        bool $all = false,
        /* Provide filter values (e.g. "label=<label>") */
        false|array $filter = false,
        /* Do not prompt for confirmation */
        bool $force = false,
    ): Process {
        $this->docker->add('prune');
        $this->docker->addIf($all, '--all');
        $this->docker->addIf($filter, '--filter', $filter);
        $this->docker->addIf($force, '--force');

        return $this->docker->runCommand();
    }

    /**
     * Usage:  docker volume rm [OPTIONS] VOLUME [VOLUME...].
     */
    public function rm(
        string $volume,
        array $volumes = [],
        /* Force the removal of one or more volumes */
        bool $force = false,
    ): Process {
        $this->docker->add('rm');
        $this->docker->addIf($force, '--force');
        $this->docker->addIf($volume, null, $volume);
        $this->docker->addIf($volumes, null, $volumes);

        return $this->docker->runCommand();
    }
}

class DockerSwarm
{
    public function __construct(
        private readonly Docker $docker,
    ) {
        $this->docker->add('swarm');
    }

    /**
     * Usage:  docker swarm init [OPTIONS].
     */
    public function init(
        /* Advertised address (format: "<ip|interface>[:port]") */
        false|string $advertiseAddr = false,
        /* Enable manager autolocking (requiring an unlock key to start a stopped manager) */
        bool $autolock = false,
        /* Availability of the node ("active", "pause", "drain") (default "active") */
        false|string $availability = false,
        /* Validity period for node certificates (ns|us|ms|s|m|h) (default 2160h0m0s) */
        false|string $certExpiry = false,
        /* Address or interface to use for data path traffic (format: "<ip|interface>") */
        false|string $dataPathAddr = false,
        /* Port number to use for data path traffic (1024 - 49151). If no value is set or is set to 0, the default port (4789) is used. */
        false|int $dataPathPort = false,
        /* ipNetSlice default address pool in CIDR format (default []) */
        bool $defaultAddrPool = false,
        /* default address pool subnet mask length (default 24) */
        false|int $defaultAddrPoolMaskLength = false,
        /* Dispatcher heartbeat period (ns|us|ms|s|m|h) (default 5s) */
        false|string $dispatcherHeartbeat = false,
        /* external-ca Specifications of one or more certificate signing endpoints */
        bool $externalCa = false,
        /* Force create a new cluster from current state */
        bool $forceNewCluster = false,
        /* Listen address (format: "<ip|interface>[:port]") (default 0.0.0.0:2377) */
        false|string $listenAddr = false,
        /* Number of additional Raft snapshots to retain */
        false|int $maxSnapshots = false,
        /* Number of log entries between Raft snapshots (default 10000) */
        false|int $snapshotInterval = false,
        /* Task history retention limit (default 5) */
        false|int $taskHistoryLimit = false,
    ): Process {
        $this->docker->add('init');
        $this->docker->addIf($advertiseAddr, '--advertise-addr', $advertiseAddr);
        $this->docker->addIf($autolock, '--autolock');
        $this->docker->addIf($availability, '--availability', $availability);
        $this->docker->addIf($certExpiry, '--cert-expiry', $certExpiry);
        $this->docker->addIf($dataPathAddr, '--data-path-addr', $dataPathAddr);
        $this->docker->addIf($dataPathPort, '--data-path-port', $dataPathPort);
        $this->docker->addIf($defaultAddrPool, '--default-addr-pool');
        $this->docker->addIf($defaultAddrPoolMaskLength, '--default-addr-pool-mask-length', $defaultAddrPoolMaskLength);
        $this->docker->addIf($dispatcherHeartbeat, '--dispatcher-heartbeat', $dispatcherHeartbeat);
        $this->docker->addIf($externalCa, '--external-ca');
        $this->docker->addIf($forceNewCluster, '--force-new-cluster');
        $this->docker->addIf($listenAddr, '--listen-addr', $listenAddr);
        $this->docker->addIf($maxSnapshots, '--max-snapshots', $maxSnapshots);
        $this->docker->addIf($snapshotInterval, '--snapshot-interval', $snapshotInterval);
        $this->docker->addIf($taskHistoryLimit, '--task-history-limit', $taskHistoryLimit);

        return $this->docker->runCommand();
    }

    /**
     * Usage:  docker swarm join [OPTIONS] HOST:PORT.
     */
    public function join(
        string $host,
        string $port,
        /* Advertised address (format: "<ip|interface>[:port]") */
        false|string $advertiseAddr = false,
        /* Availability of the node ("active", "pause", "drain") (default "active") */
        false|string $availability = false,
        /* Address or interface to use for data path traffic (format: "<ip|interface>") */
        false|string $dataPathAddr = false,
        /* Listen address (format: "<ip|interface>[:port]") (default 0.0.0.0:2377) */
        false|string $listenAddr = false,
        /* Token for entry into the swarm */
        false|string $token = false,
    ): Process {
        $this->docker->add('join');
        $this->docker->addIf($advertiseAddr, '--advertise-addr', $advertiseAddr);
        $this->docker->addIf($availability, '--availability', $availability);
        $this->docker->addIf($dataPathAddr, '--data-path-addr', $dataPathAddr);
        $this->docker->addIf($listenAddr, '--listen-addr', $listenAddr);
        $this->docker->addIf($token, '--token', $token);
        $this->docker->addIf($host, null, $host);
        $this->docker->addIf($port, null, $port);

        return $this->docker->runCommand();
    }
}

class DockerBuilderImagetools
{
    public function __construct(
        private readonly Docker $docker,
        /** Override the configured builder instance (default "default") */
        private readonly false|string $builder = false,
    ) {
        $this->docker->add('imagetools');

        $this->docker->addIf($this->builder, '--builder', $this->builder);
    }

    /**
     * Usage:  docker buildx imagetools create [OPTIONS] [SOURCE] [SOURCE...].
     */
    public function create(
        string $source,
        array $sources = [],
        /* Add annotation to the image */
        false|array $annotation = false,
        /* Append to existing manifest */
        bool $append = false,
        /* Override the configured builder instance (default "default") */
        false|string $builder = false,
        /* Show final image instead of pushing */
        bool $dryRun = false,
        /* Read source descriptor from file */
        false|array $file = false,
        /* Set type of progress output ("auto", "plain", "tty"). Use plain to show container output (default "auto") */
        false|string $progress = false,
        /* Set reference for new image */
        false|array $tag = false,
    ): Process {
        $this->docker->add('create');
        $this->docker->addIf($annotation, '--annotation', $annotation);
        $this->docker->addIf($append, '--append');
        $this->docker->addIf($builder, '--builder', $builder);
        $this->docker->addIf($dryRun, '--dry-run');
        $this->docker->addIf($file, '--file', $file);
        $this->docker->addIf($progress, '--progress', $progress);
        $this->docker->addIf($tag, '--tag', $tag);
        $this->docker->addIf($source, null, $source);
        $this->docker->addIf($sources, null, $sources);

        return $this->docker->runCommand();
    }

    /**
     * Usage:  docker buildx imagetools inspect [OPTIONS] NAME.
     */
    public function inspect(
        string $name,
        /* Override the configured builder instance (default "default") */
        false|string $builder = false,
        /* Format the output using the given Go template */
        false|string $format = false,
        /* Show original, unformatted JSON manifest */
        bool $raw = false,
    ): Process {
        $this->docker->add('inspect');
        $this->docker->addIf($builder, '--builder', $builder);
        $this->docker->addIf($format, '--format', $format);
        $this->docker->addIf($raw, '--raw');
        $this->docker->addIf($name, null, $name);

        return $this->docker->runCommand();
    }
}

class DockerBuildxImagetools
{
    public function __construct(
        private readonly Docker $docker,
        /** Override the configured builder instance */
        private readonly false|string $builder = false,
    ) {
        $this->docker->add('imagetools');

        $this->docker->addIf($this->builder, '--builder', $this->builder);
    }

    /**
     * Usage:  docker buildx imagetools create [OPTIONS] [SOURCE] [SOURCE...].
     */
    public function create(
        string $source,
        array $sources = [],
        /* Add annotation to the image */
        false|array $annotation = false,
        /* Append to existing manifest */
        bool $append = false,
        /* Override the configured builder instance */
        false|string $builder = false,
        /* Show final image instead of pushing */
        bool $dryRun = false,
        /* Read source descriptor from file */
        false|array $file = false,
        /* Set type of progress output ("auto", "plain", "tty"). Use plain to show container output (default "auto") */
        false|string $progress = false,
        /* Set reference for new image */
        false|array $tag = false,
    ): Process {
        $this->docker->add('create');
        $this->docker->addIf($annotation, '--annotation', $annotation);
        $this->docker->addIf($append, '--append');
        $this->docker->addIf($builder, '--builder', $builder);
        $this->docker->addIf($dryRun, '--dry-run');
        $this->docker->addIf($file, '--file', $file);
        $this->docker->addIf($progress, '--progress', $progress);
        $this->docker->addIf($tag, '--tag', $tag);
        $this->docker->addIf($source, null, $source);
        $this->docker->addIf($sources, null, $sources);

        return $this->docker->runCommand();
    }

    /**
     * Usage:  docker buildx imagetools inspect [OPTIONS] NAME.
     */
    public function inspect(
        string $name,
        /* Override the configured builder instance */
        false|string $builder = false,
        /* Format the output using the given Go template */
        false|string $format = false,
        /* Show original, unformatted JSON manifest */
        bool $raw = false,
    ): Process {
        $this->docker->add('inspect');
        $this->docker->addIf($builder, '--builder', $builder);
        $this->docker->addIf($format, '--format', $format);
        $this->docker->addIf($raw, '--raw');
        $this->docker->addIf($name, null, $name);

        return $this->docker->runCommand();
    }
}

class DockerTrustKey
{
    public function __construct(
        private readonly Docker $docker,
    ) {
        $this->docker->add('key');
    }

    /**
     * Usage:  docker trust key generate NAME.
     */
    public function generate(
        string $name,
        /* Directory to generate key in, defaults to current directory */
        false|string $dir = false,
    ): Process {
        $this->docker->add('generate');
        $this->docker->addIf($dir, '--dir', $dir);
        $this->docker->addIf($name, null, $name);

        return $this->docker->runCommand();
    }

    /**
     * Usage:  docker trust key load [OPTIONS] KEYFILE.
     */
    public function load(
        string $keyfile,
        /* Name for the loaded key (default "signer") */
        false|string $name = false,
    ): Process {
        $this->docker->add('load');
        $this->docker->addIf($name, '--name', $name);
        $this->docker->addIf($keyfile, null, $keyfile);

        return $this->docker->runCommand();
    }
}

class DockerTrustSigner
{
    public function __construct(
        private readonly Docker $docker,
    ) {
        $this->docker->add('signer');
    }

    /**
     * Usage:  docker trust signer add OPTIONS NAME REPOSITORY [REPOSITORY...].
     */
    public function add(
        string $name,
        string $repository,
        array $repositorys = [],
        /* Path to the signer's public key file */
        false|array $key = false,
    ): Process {
        $this->docker->add('add');
        $this->docker->addIf($key, '--key', $key);
        $this->docker->addIf($name, null, $name);
        $this->docker->addIf($repository, null, $repository);
        $this->docker->addIf($repositorys, null, $repositorys);

        return $this->docker->runCommand();
    }

    /**
     * Usage:  docker trust signer remove [OPTIONS] NAME REPOSITORY [REPOSITORY...].
     */
    public function remove(
        string $name,
        string $repository,
        array $repositorys = [],
        /* Do not prompt for confirmation before removing the most recent signer */
        bool $force = false,
    ): Process {
        $this->docker->add('remove');
        $this->docker->addIf($force, '--force');
        $this->docker->addIf($name, null, $name);
        $this->docker->addIf($repository, null, $repository);
        $this->docker->addIf($repositorys, null, $repositorys);

        return $this->docker->runCommand();
    }
}

function docker(?Context $context = null): Docker
{
    return new Docker($context ?? context());
}
