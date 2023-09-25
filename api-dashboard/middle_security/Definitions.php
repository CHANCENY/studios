<?php

namespace middle_security;

class Definitions
{
  const DEFINITIONS = [
      ""=>"Index",
      "/"=>"Index",
      "/403"=>'Forbidden',
      "/404"=>"NotFound",
      "/400"=>"BadRequest",
      "/login-dashboard"=>"AccessDashboard",
      "/shows/last-ten-days"=>'ShowsLastTenDays',
      "/movies/last-ten-days"=>'MoviesLastTenDays',
      "/logout-dashboard"=>'AccessRemover',
      "/shows/per/year"=>'ShowsYearly',
      "/movies/per/year"=>'MoviesYearly'
  ];

  const METHODS = [
      ""=>"GET",
      "/"=>"GET",
      "/403"=>'GET',
      "/404"=>"GET",
      "/400"=>"GET",
      "/login-dashboard"=>"POST",
      "/shows/last-ten-days"=>'GET',
      "/movies/last-ten-days"=>'GET',
      "/logout-dashboard"=>'GET',
      "/shows/per/year"=>'GET',
      "/movies/per/year"=>'GET'
  ];

  const ACCESS = [
      ""=>"public",
      "/"=>"public",
      "/403"=>'public',
      "/404"=>"public",
      "/400"=>"public",
      "/login-dashboard"=>"public",
      "/shows/last-ten-days"=>'private',
      "/movies/last-ten-days"=>'private',
      "/logout-dashboard"=>'public',
      "/shows/per/year"=>'private',
      "/movies/per/year"=>'private'
  ];
}