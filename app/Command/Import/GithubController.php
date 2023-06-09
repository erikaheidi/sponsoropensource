<?php

namespace App\Command\Import;

use App\GithubClient;
use Minicli\Command\CommandController;
use Librarian\Content;

class GithubController extends CommandController
{
    /**
     * @throws \Exception
     */
    public function handle(): void
    {
        $params = $this->getArgs();
        $data_path = $this->getApp()->config->profile_dir;

        if (!isset($params[3])) {
            throw new \Exception("You need to provide a valid GitHub username.");
        }

        if (!$this->getApp()->config->has('profile_dir')) {
            throw new \Exception("You must configure the profile_dir.");
        }

        if (!$this->getApp()->config->has('GITHUB_API_TOKEN')) {
            throw new \Exception("You need to configure a valid GITHUB_API_TOKEN.");
        }

        $github = new GithubClient($this->getApp()->config->GITHUB_API_TOKEN);
        $user = $params[3];

        $profile_path = $data_path . '/' . $user . '.md';
        //checks if user already exists, in this case skips
        if (is_file($profile_path)) {
            $this->getPrinter()->info("User is already in the list, skipping...");
            return;
        }

        if ($github->isSponsorable($user)) {
            $this->getPrinter()->success("User $user is sponsorable! yay!", 1);

            //make new request to obtain user info
            $user_info = $github->getUserInfo($user);
            $content = new Content($this->buildUserPage($user_info));
            $content->save($profile_path);
            $this->getPrinter()->info("Saved $user info.");
        } else {
            $this->getPrinter()->error("The user is not sponsorable or there was an error in the request.");
        }

        $this->getPrinter()->success("Import Finished.");
    }

    public function buildUserPage(array $user_data): string
    {
        $tags = [];
        $projects = [];

        //generate tags for this user's top repo languages
        foreach ($user_data['topRepositories']['nodes'] as $repository) {
            $projects[] = $repository;
            if (isset($repository['primaryLanguage']) && isset($repository['primaryLanguage']['name'])) {
                $tags[] = $repository['primaryLanguage']['name'];
            }
        }

        //front matter
        $content = "---\n";
        $content .= "title: " . $user_data['name'] . "\n";
        $content .= "description: " . $user_data['sponsorsListing']['shortDescription'] . "\n";
        $content .= "published: true\n";
        $content .= "user: " . $user_data['login'] . "\n";
        $content .= "cover_image: " . $user_data['avatarUrl'] . "\n";
        $content .= "tags: " . implode(', ', array_unique($tags)) . "\n";
        $content .= "---\n\n";

        $content .= $user_data['sponsorsListing']['fullDescription'];

        return $content;
    }

}
