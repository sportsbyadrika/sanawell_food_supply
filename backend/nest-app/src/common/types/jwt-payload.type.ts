export type JwtPayload = {
  sub: number;
  role_id: number;
  agencyId?: number | null;
  email: string;
};
