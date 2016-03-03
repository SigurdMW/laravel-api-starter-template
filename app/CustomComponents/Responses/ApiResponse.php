<?php

namespace CustomComponents\Responses;

class ApiResponse {
	
	public function json($statusCode, $message = "", $data, $headers = [])
	{

		if (! $message){
			
			if($statusCode == 200){
				$message = "OK";	
			}

			if($statusCode == 201){
				$message = "Resource successfully created.";	
			}

			if($statusCode == 400){
				$message = "Validation or similar failed.";
			}

			if($statusCode == 401){
				$message = "Not authorized.";	
			}			

			if($statusCode == 404){
				$message = "Resource not found.";
			}

			if($statusCode == 500){
				$message = "Internal server error.";
			}
			
		}

		return Response()->json([
				'status_code' 	=> $statusCode,
				'message' 		=> $message,
				'data'			=> $data
			], $statusCode, $headers);
	}
}